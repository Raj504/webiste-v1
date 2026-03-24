<?php
// app/Http/Controllers/Api/NearbyGymController.php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NearbyGymRequest;
use App\Models\Gym;
use App\Helpers\ApiResponse;

class NearbyGymController extends Controller
{
    public function index(NearbyGymRequest $request)
    {
        $lat    = $request->lat;
        $lng    = $request->lng;
        $radius = $request->input('radius', 5);

        $gyms = Gym::selectRaw("
                *,
                ROUND(
                  6371 * acos(
                    GREATEST(-1, LEAST(1,
                      cos(radians(?)) * cos(radians(lat))
                      * cos(radians(lng) - radians(?))
                      + sin(radians(?)) * sin(radians(lat))
                    ))
                  ), 2
                ) AS distance_km
            ", [$lat, $lng, $lat])
            ->where('status', 'active')
            ->having('distance_km', '<=', $radius)
            ->orderBy('distance_km')
            ->get()
            ->map(fn($gym) => [
                'id'           => $gym->id,
                'name'         => $gym->name,
                'address_text' => $gym->address_text,
                'lat'          => (float) $gym->lat,
                'lng'          => (float) $gym->lng,
                'city'         => $gym->city,
                'area'         => $gym->area,
                'distance_km'  => (float) $gym->distance_km,
                'monthly_rate' => (int) $gym->monthly_rate,
                'pricing'      => [
                    'per_day' => (int) round($gym->monthly_rate * 0.10),
                    '3_days'  => (int) round($gym->monthly_rate * 0.25),
                    '7_days'  => (int) round($gym->monthly_rate * 0.50),
                    'monthly' => (int) $gym->monthly_rate,
                ],
            ]);

        return ApiResponse::ok(
            'gyms_found',
            'Nearby gyms fetched successfully.',
            [
                'count'      => $gyms->count(),
                'radius_km'  => (float) $radius,
                'gyms'       => $gyms,
            ]
        );
    }
}