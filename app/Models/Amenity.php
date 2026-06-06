<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function gyms(): BelongsToMany
    {
        return $this->belongsToMany(Gym::class, 'gym_amenity');
    }
}