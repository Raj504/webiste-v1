<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Settlement extends Model
{
    protected $fillable = [
        'booking_id',
        'gym_id',
        'gross_amount',
        'commission_amount',
        'payout_amount',
        'commission_pct',
        'gym_upi_id',
        'payout_status',
        'paid_at',
        'razorpay_payout_id',
    ];

    protected $casts = [
        'gross_amount'      => 'integer',
        'commission_amount' => 'integer',
        'payout_amount'     => 'integer',
        'commission_pct'    => 'decimal:2',
        'paid_at'           => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isPending(): bool
    {
        return $this->payout_status === 'pending';
    }

    public function markAsPaid(?string $razorpayPayoutId = null): void
    {
        $this->update([
            'payout_status'      => 'paid',
            'paid_at'            => now(),
            'razorpay_payout_id' => $razorpayPayoutId,
        ]);
    }
}