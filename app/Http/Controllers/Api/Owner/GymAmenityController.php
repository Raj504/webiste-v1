<?php

namespace App\Http\Controllers\Api\Owner;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\AddCustomAmenityRequest;
use App\Http\Requests\Owner\SyncAmenitiesRequest;
use App\Models\Amenity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GymAmenityController extends Controller
{
    /**
     * GET /api/owner/gym/amenities
     *
     * Returns ALL amenities in the global list,
     * with is_selected=true for ones this gym has picked.
     */
    public function index(Request $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        // IDs this gym has already selected
        $selectedIds = $gym->amenities()->pluck('amenity_id')->toArray();

        $amenities = Amenity::orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get()
            ->map(fn($a) => [
                'id'          => $a->id,
                'name'        => $a->name,
                'icon'        => $a->icon,
                'is_default'  => $a->is_default,
                'is_selected' => in_array($a->id, $selectedIds),
            ]);

        return ApiResponse::ok('amenities_fetched', 'Amenities fetched.', [
            'amenities'    => $amenities,
            'selected_ids' => $selectedIds,
        ]);
    }

    /**
     * POST /api/owner/gym/amenities/sync
     *
     * Save the full selection for this gym.
     * Replaces all previous selections.
     * Body: { amenity_ids: [1, 3, 5, ...] }
     */
    public function sync(SyncAmenitiesRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        // sync() handles attach/detach automatically
        $gym->amenities()->sync($request->amenity_ids);

        $selectedIds = $gym->amenities()->pluck('amenity_id')->toArray();

        return ApiResponse::ok('amenities_saved', 'Amenities saved successfully.', [
            'selected_ids' => $selectedIds,
            'count'        => count($selectedIds),
        ]);
    }

    /**
     * POST /api/owner/gym/amenities/custom
     *
     * Add a brand new amenity to the global list
     * and auto-select it for this gym.
     * Body: { name, icon? }
     */
    public function addCustom(AddCustomAmenityRequest $request): JsonResponse
    {
        $gym = $request->user()->gym;

        if (!$gym) {
            return ApiResponse::badRequest('gym_not_found', 'Gym not found.');
        }

        // Create in global amenities table
        $amenity = Amenity::create([
            'name'       => $request->name,
            'icon'       => $request->icon ?? '🏋️',
            'is_default' => false,
        ]);

        // Auto-select for this gym
        $gym->amenities()->attach($amenity->id);

        return ApiResponse::created('amenity_added', 'Amenity added and selected for your gym.', [
            'amenity' => [
                'id'          => $amenity->id,
                'name'        => $amenity->name,
                'icon'        => $amenity->icon,
                'is_default'  => false,
                'is_selected' => true,
            ],
        ]);
    }
}