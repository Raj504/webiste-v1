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

    public function show($id)
    {
        $gym = Gym::with('owner')->where('status', 'active')->findOrFail($id);
        return response()->json(['data' => $gym]);
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
