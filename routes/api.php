<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\NearbyGymController;
use App\Http\Controllers\Api\Owner\GymMediaController;
use App\Http\Controllers\GymController;
use Illuminate\Support\Facades\Route;


Route::get('/gyms/nearby', [NearbyGymController::class, 'index']);
Route::get('/gyms/{id}/plans', [GymController::class, 'plans']);


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


Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('me',      [LoginController::class, 'me']);
    Route::get('/gyms/{id}',[GymController::class, 'show']);
    Route::put('/gyms/{id}', [GymController::class, 'update']);
});

Route::middleware(['auth:sanctum', 'role:owner'])->prefix('owner/gym')->group(function () {
 
    Route::post(   'cover/upload',   [GymMediaController::class, 'uploadCover']);
    Route::post(   'cover/unsplash', [GymMediaController::class, 'setCoverFromUnsplash']);
    Route::delete( 'cover',          [GymMediaController::class, 'removeCover']);
 
    Route::get(    'photos',                [GymMediaController::class, 'listPhotos']);
    Route::post(   'photos/upload',         [GymMediaController::class, 'uploadPhotos']);
    Route::post(   'photos/unsplash',       [GymMediaController::class, 'addPhotoFromUnsplash']);
    Route::put(    'photos/reorder',        [GymMediaController::class, 'reorderPhotos']);
    Route::delete( 'photos/{photoId}',      [GymMediaController::class, 'deletePhoto']);
 
    Route::post(   'videos/url',            [GymMediaController::class, 'saveVideoUrl']);
    Route::post(   'videos/upload',         [GymMediaController::class, 'uploadVideo']);
    Route::delete( 'videos/{source}',       [GymMediaController::class, 'removeVideo']);
});