<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\NearbyGymController;
use App\Http\Controllers\GymController;
use App\Http\Controllers\Api\Owner\GymPlanController;
use App\Http\Controllers\Api\Owner\GymAmenityController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\RazorpayWebhookController;
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

// Called by Razorpay's servers, not the FE — must stay outside auth:sanctum.
Route::post('/webhooks/razorpay', [RazorpayWebhookController::class, 'handle']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('me',      [LoginController::class, 'me']);
    Route::get('/gyms/{id}',[GymController::class, 'show']);
    Route::put('/gyms/{id}', [GymController::class, 'update']);
    Route::post('/gyms/{id}/operating-hours', [GymController::class, 'updateOperatingHours']);
    Route::get('/gyms/{id}/operating-hours', [GymController::class, 'operatingHours']);


    Route::prefix('owner/gym/plans')->group(function () {
        Route::get(    '/',         [GymPlanController::class, 'index']);    
        Route::post(   '/',         [GymPlanController::class, 'store']);    
        Route::put(    '/{planId}', [GymPlanController::class, 'update']);   
        Route::delete( '/{planId}', [GymPlanController::class, 'destroy']);  
    });

    Route::prefix('owner/gym/amenities')->group(function () {
        Route::get(  '/',        [GymAmenityController::class, 'index']);     // GET all + selected
        Route::post( '/sync',    [GymAmenityController::class, 'sync']);      // save selection
        Route::post( '/custom',  [GymAmenityController::class, 'addCustom']); // add new amenity
    });

    Route::prefix('bookings')->group(function () {
        Route::get(  '/',                [BookingController::class, 'index']);
        Route::get(  '/{id}',           [BookingController::class, 'show']);
        Route::post( '/create-order',   [BookingController::class, 'createOrder']);
        Route::post( '/verify-payment', [BookingController::class, 'verifyPayment']);
    });

});