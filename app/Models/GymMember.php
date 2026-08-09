<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymMember extends Model
{
    protected $fillable = [
        'gym_id',
        'user_id',
        'last_booking_id',
        'name',
        'phone',
        'email',
        'source',
        'start_date',
        'due_date',
        'plan_label',
        'notes',
        'last_reminder_sent_at',
    ];

    protected $casts = [
        'start_date'            => 'date',
        'due_date'               => 'date',
        'last_reminder_sent_at' => 'datetime',
    ];

    // ── Relationships ─────────────────────────────────────────────────────────

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function lastBooking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'last_booking_id');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function getStatusAttribute(): string
    {
        if ($this->due_date && $this->due_date->isPast()) {
            return 'expired';
        }

        return 'active';
    }

    public function isDueSoon(int $days = 7): bool
    {
        if (!$this->due_date || $this->due_date->isPast()) {
            return false;
        }

        return $this->due_date->diffInDays(today()) <= $days;
    }
}
