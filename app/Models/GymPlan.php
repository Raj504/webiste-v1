<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GymPlan extends Model
{
    protected $table = 'gym_plans';
 
    protected $fillable = [
        'gym_id',
        'name',
        'duration',
        'unit',
        'price',
        'is_default',
        'is_enabled',
    ];
 
    protected $casts = [
        'duration'   => 'integer',
        'price'      => 'integer',
        'is_default' => 'boolean',
        'is_enabled' => 'boolean',
    ];
 
    // ── Relationships ─────────────────────────────────────────────────────────
 
    public function gym(): BelongsTo
    {
        return $this->belongsTo(Gym::class);
    }
 
    // ── Helpers ───────────────────────────────────────────────────────────────
 
    /**
     * Auto-generate display name from duration + unit.
     * e.g. duration=3, unit=day → "3 Day Pass"
     *      duration=2, unit=month → "2 Month Pass"
     */
    public static function makeName(int $duration, string $unit): string
    {
        $label = $duration === 1
            ? ucfirst($unit)
            : $duration . ' ' . ucfirst($unit) . 's';
 
        return "{$label} Pass";
    }
 
    /**
     * Display label for API responses.
     */
    public function getDisplayLabelAttribute(): string
    {
        return self::makeName($this->duration, $this->unit);
    }
}
