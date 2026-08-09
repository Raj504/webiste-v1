<?php

namespace App\Http\Controllers\Api\Owner;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\AddGymMemberRequest;
use App\Http\Requests\Owner\UpdateGymMemberRequest;
use App\Mail\MembershipReminderMail;
use App\Models\GymMember;
use App\Services\GymMemberService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class GymMemberController extends Controller
{
    private GymMemberService $gymMemberService;

    public function __construct(GymMemberService $gymMemberService)
    {
        $this->gymMemberService = $gymMemberService;
    }

    /**
     * GET /api/owner/gym/members
     * List every member for this gym — manually-added members and day-pass
     * travelers together, soonest renewal first.
     */
    public function index(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        $members = $gym->members()
            ->orderBy('due_date')
            ->get()
            ->map(fn ($m) => $this->format($m));

        return ApiResponse::ok('members_fetched', 'Members fetched.', ['members' => $members]);
    }

    /**
     * POST /api/owner/gym/members
     * Add a member, or renew an existing one matched by phone number.
     */
    public function store(AddGymMemberRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        $member = $this->gymMemberService->addOrRenew($gym, $request->validated());

        return ApiResponse::created(
            'member_saved',
            'Member saved.',
            ['member' => $this->format($member)]
        );
    }

    /**
     * PUT /api/owner/gym/members/{memberId}
     * Edit details, or renew — sending start_date + duration_type together
     * recomputes due_date.
     */
    public function update(UpdateGymMemberRequest $request, int $memberId): JsonResponse
    {
        $gym    = $request->user()->gym;
        $member = $gym ? $gym->members()->find($memberId) : null;

        if (!$member) {
            return ApiResponse::badRequest('member_not_found', 'Member not found.');
        }

        $data    = $request->validated();
        $updates = array_intersect_key($data, array_flip(['name', 'phone', 'email', 'notes']));

        if (isset($data['start_date']) && isset($data['duration_type'])) {
            $startDate = Carbon::parse($data['start_date']);
            $updates['start_date'] = $startDate;
            $updates['due_date']   = $this->gymMemberService->calculateDueDate(
                $startDate,
                $data['duration_type'],
                $data['custom_days'] ?? null
            );
            $updates['plan_label'] = $this->gymMemberService->makePlanLabel(
                $data['duration_type'],
                $data['custom_days'] ?? null
            );
        }

        $member->update($updates);

        return ApiResponse::ok(
            'member_updated',
            'Member updated.',
            ['member' => $this->format($member->fresh())]
        );
    }

    /**
     * DELETE /api/owner/gym/members/{memberId}
     */
    public function destroy(Request $request, int $memberId): JsonResponse
    {
        $gym    = $request->user()->gym;
        $member = $gym ? $gym->members()->find($memberId) : null;

        if (!$member) {
            return ApiResponse::badRequest('member_not_found', 'Member not found.');
        }

        $member->delete();

        return ApiResponse::ok('member_deleted', 'Member deleted.');
    }

    /**
     * POST /api/owner/gym/members/{memberId}/send-reminder
     * Owner-triggered email reminder — no automated scheduler yet.
     *
     * TODO: once a scheduler exists, auto-send this to due-soon/overdue members
     * instead of relying on the owner remembering to click the button.
     */
    public function sendReminder(Request $request, int $memberId): JsonResponse
    {
        $gym    = $request->user()->gym;
        $member = $gym ? $gym->members()->find($memberId) : null;

        if (!$member) {
            return ApiResponse::badRequest('member_not_found', 'Member not found.');
        }

        if (!$member->email) {
            return ApiResponse::badRequest('no_email', 'This member has no email on file.');
        }

        try {
            Mail::to($member->email)->send(new MembershipReminderMail($member));
        } catch (\Exception $e) {
            report($e);
            return ApiResponse::serverError('Failed to send reminder email. Please try again.');
        }

        $member->update(['last_reminder_sent_at' => now()]);

        return ApiResponse::ok(
            'reminder_sent',
            'Reminder sent.',
            ['member' => $this->format($member->fresh())]
        );
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function format(GymMember $member): array
    {
        return [
            'id'                    => $member->id,
            'name'                  => $member->name,
            'phone'                 => $member->phone,
            'email'                 => $member->email,
            'source'                => $member->source,
            // ISO format — FE computes days-left/progress from these, not just display
            'start_date'            => $member->start_date->format('Y-m-d'),
            'due_date'              => $member->due_date->format('Y-m-d'),
            'status'                => $member->status,
            'plan_label'            => $member->plan_label,
            'notes'                 => $member->notes,
            'last_reminder_sent_at' => $member->last_reminder_sent_at
                ? $member->last_reminder_sent_at->format('d M Y, h:i A')
                : null,
        ];
    }
}
