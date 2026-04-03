<?php

namespace App\Http\Controllers;
use App\Models\Gym;
use App\Models\User;
use App\Http\Requests\UpdateGymRequest;

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
        $gym = Gym::where('status', 'active')->findOrFail($id);
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
}
