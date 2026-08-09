<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettlementController extends Controller
{
    /**
     * GET /admin/settlements
     *
     * Every settlement (one per paid booking) with enough context to manually
     * transfer the gym owner's payout and check it off. ?status=pending|paid
     * filters the list; default shows everything.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $settlements = Settlement::with(['booking.user', 'booking.plan', 'gym.owner'])
            ->when(in_array($status, ['pending', 'paid'], true), fn ($q) => $q->where('payout_status', $status))
            ->latest()
            ->paginate(50)
            ->withQueryString();

        $pendingCount  = Settlement::where('payout_status', 'pending')->count();
        $pendingAmount = Settlement::where('payout_status', 'pending')->sum('payout_amount');

        return view('admin.settlements', compact('settlements', 'status', 'pendingCount', 'pendingAmount'));
    }

    /**
     * POST /admin/settlements/{settlement}/toggle-paid
     *
     * Flips a settlement between pending and paid. Used by the checkbox in the
     * admin view once you've actually sent the UPI transfer.
     */
    public function togglePaid(Settlement $settlement): RedirectResponse
    {
        if ($settlement->payout_status === 'paid') {
            $settlement->update(['payout_status' => 'pending', 'paid_at' => null]);
        } else {
            $settlement->markAsPaid();
        }

        return redirect()
            ->route('admin.settlements')
            ->with('status', 'Settlement #' . $settlement->id . ' updated.');
    }
}
