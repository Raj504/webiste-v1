<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Gym extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'city',
        'area',
        'address_text',
        'lat', 
        'lng',
        'monthly_rate',
        'upi_id',
        'status',
        'mapbox_place_id',
        'description'
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

    public function plans(): HasMany
    {
        return $this->hasMany(GymPlan::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(GymMember::class);
    }

    public function operatingHours()
    {
        return $this->hasMany(GymOperatingHour::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'gym_amenity');
    }
}