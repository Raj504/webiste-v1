<?php

namespace Database\Seeders;

use App\Models\Amenity;
use Illuminate\Database\Seeder;

class AmenitiesSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['icon' => '🧊', 'name' => 'AC'],
            ['icon' => '🔒', 'name' => 'Lockers'],
            ['icon' => '🚿', 'name' => 'Shower'],
        ];

        $others = [
            ['icon' => '🅿️', 'name' => 'Parking'],
            ['icon' => '👨‍💼', 'name' => 'Trainer'],
            ['icon' => '💪', 'name' => 'Free Weights'],
            ['icon' => '🏊', 'name' => 'Pool'],
            ['icon' => '🧘', 'name' => 'Yoga Room'],
            ['icon' => '🥤', 'name' => 'Protein Bar'],
            ['icon' => '📺', 'name' => 'TV / Music'],
            ['icon' => '🌐', 'name' => 'WiFi'],
            ['icon' => '🧺', 'name' => 'Towel Service'],
        ];

        foreach ($defaults as $amenity) {
            Amenity::firstOrCreate(
                ['name' => $amenity['name']],
                ['icon' => $amenity['icon'], 'is_default' => true]
            );
        }

        foreach ($others as $amenity) {
            Amenity::firstOrCreate(
                ['name' => $amenity['name']],
                ['icon' => $amenity['icon'], 'is_default' => false]
            );
        }
    }
}