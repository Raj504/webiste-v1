<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'gym_id',
        'gym_member_id',
        'booking_id',
        'source',
        'gym_name_snapshot',
        'gym_address_snapshot',
        'member_name',
        'member_phone',
        'member_email',
        'plan_label',
        'amount',
        'payment_status',
        'issued_date',
    ];

    protected $casts = [
        'amount'      => 'integer',
        'issued_date' => 'date',
    ];

    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }

    public function gymMember(): BelongsTo
    {
        return $this->belongsTo(GymMember::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
