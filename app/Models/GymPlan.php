<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GymPlan extends Model
{
    use HasFactory;
    protected $fillable = ['gym_id', 'name', 'type', 'price'];

    public function plans()
        {
            return $this->hasMany(GymPlan::class);
        }

}
