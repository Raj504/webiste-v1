<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterOwnerRequest;
use App\Http\Requests\Auth\RegisterTravelerRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Services\Auth\OtpService;
use App\Services\Auth\RegisterService;
use App\Services\Auth\TempTokenService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private $otpService;
    private $tempTokenService;
    private $registerService;

    public function __construct(
        OtpService $otpService,
        TempTokenService $tempTokenService,
        RegisterService $registerService
    ) {
        $this->otpService = $otpService;
        $this->tempTokenService = $tempTokenService;
        $this->registerService = $registerService;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 1 — Send OTP
    //
    // POST /api/auth/send-otp
    // Body: { phone, role }
    //
    // The DEV BYPASS is inside OtpService — submitting otp='0000' will
    // always pass in non-production. No changes needed here.
    // ─────────────────────────────────────────────────────────────────────────
    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        try {
            $this->otpService->send(
                $request->phone,
                $request->role,
            );
        } catch (\RuntimeException $e) {
            // Rate-limit exceeded
            return ApiResponse::tooManyRequests($e->getMessage());
        }

        return ApiResponse::ok(
            'otp_sent',
            'OTP sent to your mobile number.',
            [
                'phone'      => $this->maskPhone($request->phone),
                'expires_in' => 300, // seconds
            ],
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 2 — Verify OTP
    //
    // POST /api/auth/verify-otp
    // Body: { phone, otp, role }
    //
    // Returns a temp_token (15 min TTL) to be sent with the register call.
    // ─────────────────────────────────────────────────────────────────────────
    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $otpRecord = $this->otpService->verify(
            $request->phone,
            $request->otp,
            $request->role,
        );

        if (!$otpRecord) {
            return ApiResponse::unauthorized(
                'otp_invalid',
                'The OTP you entered is incorrect or has expired. Please try again.',
            );
        }

        $tempToken = $this->tempTokenService->issue(
            $request->phone,
            $request->role,
        );

        return ApiResponse::ok(
            'otp_verified',
            'Phone number verified successfully.',
            [
                'temp_token' => $tempToken,
                'expires_in' => 900, // 15 minutes
            ],
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 3a — Register Traveler
    //
    // POST /api/auth/register/traveler
    // Body: { temp_token, first_name, last_name, home_city? }
    // ─────────────────────────────────────────────────────────────────────────
    public function registerTraveler(RegisterTravelerRequest $request): JsonResponse
    {
        $payload = $this->tempTokenService->consume($request->temp_token);

        if (!$payload || $payload['role'] !== 'traveler') {
            return ApiResponse::unauthorized(
                'temp_token_invalid',
                'Your verification session has expired. Please verify your phone again.',
            );
        }

        try {
            ['user' => $user, 'token' => $token] = $this->registerService->registerTraveler(
                $payload['phone'],
                $request->validated(),
            );
        } catch (\RuntimeException $e) {
            return ApiResponse::conflict(
                'phone_already_registered',
                $e->getMessage(),
            );
        }

        return ApiResponse::created(
            'traveler_registered',
            'Account created. Welcome to GymPass India!',
            [
                'access_token' => $token->plainTextToken,
                'token_type'   => 'Bearer',
                'user' => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'phone'     => $user->phone,
                    'role'      => $user->role,
                    'home_city' => $user->home_city,
                ],
            ],
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 3b — Register Owner
    //
    // POST /api/auth/register/owner
    // Body: { temp_token, owner_name, gym_name, city, area, monthly_rate,
    //         upi_id?, terms }
    // ─────────────────────────────────────────────────────────────────────────
    public function registerOwner(RegisterOwnerRequest $request): JsonResponse
    {
        $payload = $this->tempTokenService->consume($request->temp_token);

        if (!$payload || $payload['role'] !== 'owner') {
            return ApiResponse::unauthorized(
                'temp_token_invalid',
                'Your verification session has expired. Please verify your phone again.',
            );
        }

        try {
            ['user' => $user, 'gym' => $gym, 'token' => $token] = $this->registerService->registerOwner(
                $payload['phone'],
                $request->validated(),
            );
        } catch (\RuntimeException $e) {
            return ApiResponse::conflict(
                'phone_already_registered',
                $e->getMessage(),
            );
        }

        return ApiResponse::created(
            'owner_registered',
            'Gym registered! We will verify your listing within 24 hours.',
            [
                'access_token' => $token->plainTextToken,
                'token_type'   => 'Bearer',
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'phone' => $user->phone,
                    'role'  => $user->role,
                ],
                'gym' => [
                    'id'           => $gym->id,
                    'name'         => $gym->name,
                    'address_text' => $gym->address_text,
                    'lat'          => $gym->lat,
                    'lng'          => $gym->lng,
                    'city'         => $gym->city,
                    'area'         => $gym->area,
                    'monthly_rate' => $gym->monthly_rate,
                    'status'       => $gym->status,
                    'pricing'      => [
                        'per_day'  => (int) round($gym->monthly_rate * 0.10),
                        '3_days'   => (int) round($gym->monthly_rate * 0.25),
                        '7_days'   => (int) round($gym->monthly_rate * 0.50),
                        'monthly'  => $gym->monthly_rate,
                    ],
                ],
            ],
        );
    }

    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Mask the last 6 digits for display: 98765 43210 → 98765 XXXXX
     */
    private function maskPhone(string $phone): string
    {
        return substr($phone, 0, 4) . 'XXXXXX';
    }
}