<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\CheckIn;
use App\Models\Gym;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CheckInController extends Controller
{
    /**
     * GET /api/gyms/check-in/{token}
     * Pure lookup — resolves the QR token to a gym so the confirmation page
     * can show "Checking in at {Gym}" before the traveler commits to anything.
     * Records nothing.
     */
    public function show(Request $request, string $token): JsonResponse
    {
        $gym = $this->resolveGym($token);
        if (!$gym) {
            return ApiResponse::badRequest('invalid_qr', 'This QR code is invalid or no longer active.');
        }

        return ApiResponse::ok('gym_found', 'Gym found.', [
            'gym' => [
                'id'   => $gym->id,
                'name' => $gym->name,
                'city' => $gym->city,
            ],
        ]);
    }

    /**
     * POST /api/gyms/check-in/{token}
     * The actual check-in. Every denial reason below is enforced here, in
     * order, before a check_ins row is ever written. No override, no
     * exceptions — a traveler without a valid, currently-active, paid
     * booking at this specific gym is always denied.
     */
    public function store(Request $request, string $token): JsonResponse
    {
        $gym = $this->resolveGym($token);
        if (!$gym) {
            return ApiResponse::badRequest('invalid_qr', 'This QR code is invalid or no longer active.');
        }

        $user = $request->user();

        $bookings = Booking::where('user_id', $user->id)
            ->where('gym_id', $gym->id)
            ->get();

        if ($bookings->isEmpty()) {
            return ApiResponse::badRequest(
                'no_booking',
                "You don't have a pass at this gym. Book one to check in.",
            );
        }

        $activeBooking = $bookings->where('status', 'paid')
            ->filter(fn ($b) => $b->isActive())
            ->sortBy('end_date')
            ->first();

        if (!$activeBooking) {
            return $this->denyWithBestReason($bookings);
        }

        // Idempotent — a double-tap, page refresh, or accidental re-scan
        // within 30 minutes doesn't create a second row.
        $recentCheckIn = CheckIn::where('user_id', $user->id)
            ->where('gym_id', $gym->id)
            ->where('scanned_at', '>=', now()->subMinutes(30))
            ->latest('scanned_at')
            ->first();

        if ($recentCheckIn) {
            return ApiResponse::ok('already_checked_in', 'You already checked in recently.', [
                'check_in' => $this->format($recentCheckIn, $gym),
            ]);
        }

        $checkIn = CheckIn::create([
            'booking_id' => $activeBooking->id,
            'gym_id'     => $gym->id,
            'user_id'    => $user->id,
            'scanned_at' => now(),
        ]);

        return ApiResponse::ok('checked_in', 'Checked in at ' . $gym->name . '!', [
            'check_in' => $this->format($checkIn, $gym),
        ]);
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function resolveGym(string $token): ?Gym
    {
        return Gym::where('qr_token', $token)->where('status', 'active')->first();
    }

    /**
     * The traveler has bookings at this gym, but none are currently a valid,
     * active, paid pass — figure out the most relevant reason why not.
     */
    private function denyWithBestReason($bookings): JsonResponse
    {
        $paidBookings = $bookings->where('status', 'paid');

        if ($paidBookings->isEmpty()) {
            return ApiResponse::badRequest(
                'not_paid',
                'Your booking at this gym was never completed or was cancelled.',
            );
        }

        $notYetActive = $paidBookings->filter(fn ($b) => $b->start_date->isFuture())
            ->sortBy('start_date')
            ->first();

        if ($notYetActive) {
            return ApiResponse::badRequest(
                'not_yet_active',
                'Your pass starts on ' . $notYetActive->start_date->format('d M Y') . '.',
            );
        }

        $mostRecentlyExpired = $paidBookings->sortByDesc('end_date')->first();

        return ApiResponse::badRequest(
            'expired',
            'Your pass expired on ' . $mostRecentlyExpired->end_date->format('d M Y') . '.',
        );
    }

    private function format(CheckIn $checkIn, Gym $gym): array
    {
        return [
            'gym_name'   => $gym->name,
            'scanned_at' => $checkIn->scanned_at->format('d M Y, h:i A'),
        ];
    }
}
