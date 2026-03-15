<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth — Public routes (no token required)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    // ── Signup flow ──────────────────────────────────────────────────────────

    // Step 1 — request OTP
    Route::post('send-otp', [AuthController::class, 'sendOtp']);

    // Step 2 — verify OTP, receive temp_token
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

    // Step 3 — complete registration (consumes temp_token, issues Sanctum token)
    Route::post('register/traveler', [AuthController::class, 'registerTraveler']);
    Route::post('register/owner',    [AuthController::class, 'registerOwner']);

    // ── Login flow ───────────────────────────────────────────────────────────

    // Step 1 — request OTP for login
    Route::post('login/send-otp', [LoginController::class, 'sendOtp']);

    // Step 2 — verify OTP + receive Sanctum token
    Route::post('login', [LoginController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Auth — Protected routes (Sanctum token required)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->prefix('auth')->group(function () {

    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('me',      [LoginController::class, 'me']);
});