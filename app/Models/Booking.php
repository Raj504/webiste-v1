<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Settlement;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'gym_id',
        'gym_plan_id',
        'booking_ref',
        'start_date',
        'end_date',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'amount',
        'status',
        'qr_code',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'amount'     => 'integer',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(GymPlan::class, 'gym_plan_id');
    }

    public function settlement(): HasOne
    {
        return $this->hasOne(Settlement::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isActive(): bool
    {
        return $this->status === 'paid'
            && $this->start_date->lte(today())
            && $this->end_date->gte(today());
    }
}