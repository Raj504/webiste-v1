<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, SoftDeletes;

    protected $fillable = [
        'role',
        'phone',
        'name',
        'email',
        'home_city',
        'avatar',
    ];

    protected $hidden = [
        'remember_token',
    ];


    public function gym(): HasOne
    {
        return $this->hasOne(Gym::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isTraveler(): bool
    {
        return $this->role === 'traveler';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }
}