<?php

namespace App\Http\Controllers;
use App\Models\Gym;
use App\Models\User;
use App\Http\Requests\UpdateGymRequest;
use App\Models\GymOperatingHour;
use App\Helpers\ApiResponse;

use App\Http\Requests\UpdateGymOperatingHoursRequest;

use Illuminate\Http\Request;

class GymController extends Controller
{
    public function bookings(){
        return view('bookings');
    }

    public function QrScanner(){
        return view('qr-scanner');
    }

    public function members(){
        return view('members');
    }

    public function payouts(){
        return view('payouts');
    }

    public function gymSettings(){
        return view('gym-settings');
    }

    public function reviews(){
        return view('reviews');
    }

    public function analytics(){
        return view('analytics');
    }

    /**
     * GET /api/gyms/{id}
     *
     * Public: a traveller has to be able to read a gym's page before they
     * sign up, so this route sits outside auth:sanctum.
     *
     * Because it is public we build the response field by field instead of
     * returning the model. Two columns must never leave the server:
     *   - qr_token  is the gym's check-in secret; anyone holding it could
     *               forge check-ins.
     *   - upi_id    is where the owner's money goes.
     * Returning $gym directly would have published both.
     */
    public function show(Request $request, $id)
    {
        $gym = Gym::with(['amenities', 'operatingHours'])
            ->where('status', 'active')
            ->findOrFail($id);

        $data = [
            'id'           => $gym->id,
            'name'         => $gym->name,
            'description'  => $gym->description,
            'address_text' => $gym->address_text,
            'area'         => $gym->area,
            'city'         => $gym->city,
            'lat'          => (float) $gym->lat,
            'lng'          => (float) $gym->lng,
            'monthly_rate' => (int) $gym->monthly_rate,
            'pricing'      => $gym->pricing,

            // The owner maintains this under "public gym listing information",
            // so it is the gym's contact number, not a private one.
            'phone'        => $gym->owner ? $gym->owner->phone : null,

            'amenities' => $gym->amenities->map(fn($amenity) => [
                'id'   => $amenity->id,
                'name' => $amenity->name,
                'icon' => $amenity->icon,
            ])->values(),

            'operating_hours' => $gym->operatingHours->map(fn($hour) => [
                'day'    => $hour->day,
                'open'   => $hour->open_time,
                'close'  => $hour->close_time,
                'closed' => (bool) $hour->is_closed,
            ])->values(),
        ];

        // The owner's own Settings screen loads this same endpoint and edits
        // the owner name and phone, so those extra fields go to them only.
        $viewer = auth('sanctum')->user();

        if ($viewer != null && $viewer->id === $gym->user_id) {
            $data['owner'] = [
                'id'    => $gym->owner->id,
                'name'  => $gym->owner->name,
                'phone' => $gym->owner->phone,
                'role'  => $gym->owner->role,
            ];
            $data['upi_id'] = $gym->upi_id;
        }

        return response()->json(['data' => $data]);
    }

    public function plans($id)
    {
        $gym = Gym::where('status', 'active')->findOrFail($id);

        return response()->json([
            'data' => $gym->plans
        ]);
    }

    public function update(UpdateGymRequest $request, $id)
    {
        $gym = Gym::where('user_id', auth()->id())
                ->findOrFail($id);
        // Update gym fields
        $gym->update($request->safe()->except(['owner_name', 'phone']));
        // Update user fields
        $userUpdate = [];
    
        if ($request->filled('owner_name')) $userUpdate['name']  = $request->owner_name;
        if ($request->filled('phone'))      $userUpdate['phone'] = $request->phone;
    
        if (!empty($userUpdate)) {
            User::where('id', auth()->id())->update($userUpdate);

        }
    
        $user = auth()->user();

        return response()->json([
            'data' => [
                'gym'   => $gym->fresh(),
                'owner' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'phone' => $user->phone,
                    'role'  => $user->role,
                ],
            ]
        ]);
    }

    public function updateOperatingHours(UpdateGymOperatingHoursRequest $request, $id)
    {   
        $gym = Gym::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        // delete old rows
        $gym->operatingHours()->delete();

        // insert new rows
        foreach ($request->hours as $hour) {

            $gym->operatingHours()->create([
                'day'        => $hour['day'],
                'open_time'  => $hour['closed'] ? null : $hour['open'],
                'close_time' => $hour['closed'] ? null : $hour['close'],
                'is_closed'  => $hour['closed'],
            ]);
        }

        return response()->json([
            'message' => 'Operating hours updated successfully',
            'data' => $gym->operatingHours
        ]);
    }

    public function operatingHours($id)
    {
        try {
    
            $gym = Gym::where('user_id', auth()->id())
                ->where('id', $id)
                ->with('operatingHours')
                ->firstOrFail();
    
            $hours = $gym->operatingHours->map(function ($hour) {
                return [
                    'day'    => $hour->day,
                    'open'   => $hour->open_time,
                    'close'  => $hour->close_time,
                    'closed' => (bool) $hour->is_closed,
                ];
            });
    
            return response()->json([
                'message' => 'Operating hours fetched successfully',
                'data'    => $hours
            ]);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    
            return ApiResponse::badRequest(
                'gym_not_found',
                'Gym not found.'
            );
    
        } catch (\Exception $e) {
    
            report($e);
    
            return ApiResponse::serverError();
        }
    }
}
