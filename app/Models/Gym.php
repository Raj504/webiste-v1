<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gym extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'city',
        'area',
        'monthly_rate',
        'upi_id',
        'status',
    ];

    protected $casts = [
        'monthly_rate' => 'integer',
    ];

    // ─────────────────────────────────────────

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Derived pricing — all calculated from monthly_rate.
     * No separate columns needed; change monthly_rate and all prices follow.
     */
    public function getPricingAttribute(): array
    {
        return [
            'per_day' => (int) round($this->monthly_rate * 0.10),
            '3_days'  => (int) round($this->monthly_rate * 0.25),
            '7_days'  => (int) round($this->monthly_rate * 0.50),
            'monthly' => $this->monthly_rate,
        ];
    }
}