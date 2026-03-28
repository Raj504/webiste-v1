<?php

namespace App\Http\Controllers;
use App\Models\Gym;

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
        $gym = Gym::with('plans')
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json(['data' => $gym]);
    }
}
