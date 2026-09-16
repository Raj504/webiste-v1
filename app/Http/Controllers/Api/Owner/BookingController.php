<?php

namespace App\Http\Controllers\Api\Owner;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * GET /api/owner/gym/bookings
     * List every booking for this gym, plus summary stats for the dashboard.
     */
    public function index(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        $bookings = $gym->bookings()
            ->with(['user', 'plan'])
            ->latest()
            ->get();

        return ApiResponse::ok('bookings_fetched', 'Bookings fetched.', [
            'bookings' => $bookings->map(fn ($b) => $this->format($b)),
            'stats'    => $this->stats($gym),
        ]);
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function format(Booking $booking): array
    {
        return [
            'id'          => $booking->id,
            'user_name'   => $booking->user->name,
            'user_city'   => $booking->user->home_city,
            'plan_name'   => $booking->plan->name,
            'amount'      => $booking->amount,
            'booked_at'   => $booking->created_at->format('d M Y, h:i A'),
            'valid_until' => $booking->end_date->format('d M Y'),
            'status'      => $this->displayStatus($booking),
        ];
    }

    private function displayStatus(Booking $booking): string
    {
        if ($booking->status === 'paid') {
            return $booking->isActive() ? 'active' : 'expired';
        }

        return $booking->status;
    }

    private function stats($gym): array
    {
        $monthBookings = $gym->bookings()->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $monthRevenue = $gym->bookings()->where('status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $activePasses = $gym->bookings()->where('status', 'paid')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->count();

        $pendingToday = $gym->bookings()->where('status', 'pending')
            ->whereDate('created_at', today())
            ->count();

        return [
            'month_bookings' => $monthBookings,
            'month_revenue'  => (int) $monthRevenue,
            'active_passes'  => $activePasses,
            'pending_today'  => $pendingToday,
        ];
    }
}
