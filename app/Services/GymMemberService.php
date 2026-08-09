<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Gym;
use App\Models\GymMember;
use Carbon\Carbon;

class GymMemberService
{
    /**
     * Add a new member, or renew an existing one — matched by phone number
     * within the gym, so a repeat/returning member reuses the same row
     * instead of creating a duplicate.
     */
    public function addOrRenew(Gym $gym, array $data): GymMember
    {
        $startDate = Carbon::parse($data['start_date']);
        $dueDate   = $this->calculateDueDate($startDate, $data['duration_type'], $data['custom_days'] ?? null);
        $planLabel = $this->makePlanLabel($data['duration_type'], $data['custom_days'] ?? null);

        $member = GymMember::where('gym_id', $gym->id)
            ->where('phone', $data['phone'])
            ->first();

        if ($member) {
            $member->update([
                'name'       => $data['name'],
                'email'      => $data['email'] ?? $member->email,
                'start_date' => $startDate,
                'due_date'   => $dueDate,
                'plan_label' => $planLabel,
                'notes'      => $data['notes'] ?? $member->notes,
            ]);

            return $member;
        }

        return GymMember::create([
            'gym_id'     => $gym->id,
            'name'       => $data['name'],
            'phone'      => $data['phone'],
            'email'      => $data['email'] ?? null,
            'source'     => 'manual',
            'start_date' => $startDate,
            'due_date'   => $dueDate,
            'plan_label' => $planLabel,
            'notes'      => $data['notes'] ?? null,
        ]);
    }

    /**
     * Keep the members list current whenever a day-pass booking is paid.
     * Called from BookingService::activate(). Upserts by (gym_id, phone) —
     * a traveler who books again just gets their existing row refreshed,
     * and a manually-added local member who later takes a pass keeps their
     * original "manual" source instead of it being overwritten.
     */
    public function syncFromBooking(Booking $booking): void
    {
        $traveler = $booking->user;

        if (!$traveler || !$traveler->phone) {
            return;
        }

        $member = GymMember::where('gym_id', $booking->gym_id)
            ->where('phone', $traveler->phone)
            ->first();

        $attributes = [
            'name'            => $traveler->name,
            'user_id'         => $traveler->id,
            'last_booking_id' => $booking->id,
            'start_date'      => $booking->start_date,
            'due_date'        => $booking->end_date,
            'plan_label'      => $booking->plan->name,
        ];

        if ($member) {
            // Don't clobber an email already on file with a blank one
            if (!$member->email && $traveler->email) {
                $attributes['email'] = $traveler->email;
            }

            $member->update($attributes);
            return;
        }

        GymMember::create(array_merge($attributes, [
            'gym_id' => $booking->gym_id,
            'phone'  => $traveler->phone,
            'email'  => $traveler->email,
            'source' => 'booking',
        ]));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function calculateDueDate(Carbon $startDate, string $durationType, ?int $customDays = null): Carbon
    {
        switch ($durationType) {
            case '1_month':
                return (clone $startDate)->addMonth();

            case '3_months':
                return (clone $startDate)->addMonths(3);

            case '6_months':
                return (clone $startDate)->addMonths(6);

            case '12_months':
                return (clone $startDate)->addMonths(12);

            case 'custom':
                return (clone $startDate)->addDays($customDays ?? 30);

            default:
                return (clone $startDate)->addMonth();
        }
    }

    public function makePlanLabel(string $durationType, ?int $customDays): string
    {
        switch ($durationType) {
            case '1_month':
                return '1 Month';

            case '3_months':
                return '3 Months';

            case '6_months':
                return '6 Months';

            case '12_months':
                return '12 Months';

            case 'custom':
                return 'Custom (' . ($customDays ?? 0) . ' days)';

            default:
                return 'Custom';
        }
    }
}
