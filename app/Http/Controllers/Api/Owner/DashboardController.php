<?php

namespace App\Http\Controllers\Api\Owner;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Settlement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * GET /api/owner/gym/dashboard
     * Everything the "Today at a glance" dashboard page needs, in one call.
     */
    public function index(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;
        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        return ApiResponse::ok('dashboard_fetched', 'Dashboard fetched.', [
            'stats'                => $this->stats($gym),
            'revenue_chart'        => $this->revenueChart($gym),
            'new_members_this_week' => $this->newMembersThisWeek($gym),
            'recent_bookings'      => $this->recentBookings($gym),
            'pending_payout'       => $this->pendingPayout($gym),
        ]);
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function stats($gym): array
    {
        $todayBookings = $gym->bookings()->whereDate('created_at', today())->count();

        $todayRevenue = $gym->bookings()->where('status', 'paid')
            ->whereDate('created_at', today())
            ->sum('amount');

        $activeMembers = $gym->members()
            ->where(function ($q) {
                $q->whereNull('due_date')->orWhereDate('due_date', '>=', today());
            })
            ->count();

        return [
            'today_bookings' => $todayBookings,
            'today_revenue'  => (int) $todayRevenue,
            'active_members' => $activeMembers,
        ];
    }

    private function revenueChart($gym): array
    {
        $chart = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = today()->subDays($i);

            $amount = $gym->bookings()->where('status', 'paid')
                ->whereDate('created_at', $date)
                ->sum('amount');

            $chart[] = [
                'day'    => $date->format('D'),
                'amount' => (int) $amount,
            ];
        }

        return $chart;
    }

    private function newMembersThisWeek($gym): array
    {
        return $gym->members()
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(4)
            ->get()
            ->map(fn ($m) => [
                'name'       => $m->name,
                'plan_label' => $m->plan_label,
                'joined'     => $m->created_at->diffForHumans(),
            ])
            ->all();
    }

    private function recentBookings($gym): array
    {
        return $gym->bookings()
            ->with(['user', 'plan'])
            ->latest()
            ->take(4)
            ->get()
            ->map(fn ($b) => [
                'id'        => $b->id,
                'user_name' => $b->user->name,
                'user_city' => $b->user->home_city,
                'plan_name' => $b->plan->name,
                'amount'    => $b->amount,
                'status'    => $this->displayBookingStatus($b),
            ])
            ->all();
    }

    private function displayBookingStatus(Booking $booking): string
    {
        if ($booking->status === 'paid') {
            return $booking->isActive() ? 'active' : 'expired';
        }

        return $booking->status;
    }

    private function pendingPayout($gym): array
    {
        $amount = Settlement::where('gym_id', $gym->id)
            ->where('payout_status', 'pending')
            ->sum('payout_amount');

        return [
            'amount' => (int) $amount,
            'upi_id' => $gym->upi_id,
        ];
    }
}
