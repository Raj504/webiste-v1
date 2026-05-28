<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\NearbyGymController;
use App\Http\Controllers\GymController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth — Public routes (no token required)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    Route::post('send-otp', [AuthController::class, 'sendOtp']);

    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

    Route::post('register/traveler', [AuthController::class, 'registerTraveler']);
    Route::post('register/owner',    [AuthController::class, 'registerOwner']);


    Route::post('login/send-otp', [LoginController::class, 'sendOtp']);

    Route::post('login', [LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Auth — Protected routes (Sanctum token required)
|--------------------------------------------------------------------------
*/
Route::get('/gyms/nearby', [NearbyGymController::class, 'index']);
Route::get('/gyms/{id}/plans', [GymController::class, 'plans']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('me',      [LoginController::class, 'me']);
    Route::get('/gyms/{id}',[GymController::class, 'show']);
    Route::put('/gyms/{id}', [GymController::class, 'update']);
    Route::post('/gyms/{id}/operating-hours', [GymController::class, 'updateOperatingHours']);

});