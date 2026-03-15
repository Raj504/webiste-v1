<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\SendOtpRequest;
use App\Services\Auth\LoginService;
use App\Services\Auth\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    private $otpService;
    private $loginService;

    public function __construct(
        OtpService $otpService,
        LoginService $loginService
    ) {
        $this->otpService = $otpService;
        $this->loginService = $loginService;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 1 — Request OTP for login
    //
    // POST /api/auth/login/send-otp
    // Body: { phone, role }
    //
    // Reuses the same OtpService as signup — same send + rate-limit logic.
    // We validate the user exists first so we don't send OTPs to unregistered
    // numbers (gives a clear "not registered" error instead of silent void).
    // ─────────────────────────────────────────────────────────────────────────
    public function sendOtp(SendOtpRequest $request): JsonResponse
    {
        // Guard: tell the user to sign up if they don't exist yet
        $user = $this->loginService->findUser($request->phone, $request->role);

        if (!$user) {
            return ApiResponse::badRequest(
                'user_not_found',
                'No account found for this number. Please sign up first.',
            );
        }

        try {
            $this->otpService->send($request->phone, $request->role);
        } catch (\RuntimeException $e) {
            return ApiResponse::tooManyRequests($e->getMessage());
        }

        return ApiResponse::ok(
            'otp_sent',
            'OTP sent to your registered mobile number.',
            [
                'phone'      => $this->maskPhone($request->phone),
                'expires_in' => 300,
            ],
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Step 2 — Verify OTP + login
    //
    // POST /api/auth/login
    // Body: { phone, otp, role }
    //
    // Login is a single step (unlike signup which splits OTP + register).
    // OTP verified → user looked up → Sanctum token issued in one shot.
    // ─────────────────────────────────────────────────────────────────────────
    public function login(LoginRequest $request): JsonResponse
    {
        $otpRecord = $this->otpService->verify(
            $request->phone,
            $request->otp,
            $request->role,
        );

        if (!$otpRecord) {
            return ApiResponse::unauthorized(
                'otp_invalid',
                'The OTP you entered is incorrect or has expired.',
            );
        }

        $user = $this->loginService->findUser($request->phone, $request->role);

        // Edge case: OTP passed but user was deleted between send and verify
        if (!$user) {
            return ApiResponse::badRequest(
                'user_not_found',
                'No account found for this number. Please sign up first.',
            );
        }

        ['token' => $token] = $this->loginService->loginUser($user);

        $data = [
            'access_token' => $token->plainTextToken,
            'token_type'   => 'Bearer',
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'phone' => $user->phone,
                'role'  => $user->role,
            ],
        ];

        // Include gym info for owners so the dashboard can boot without an extra call
        if ($user->isOwner() && $user->gym) {
            $data['gym'] = [
                'id'           => $user->gym->id,
                'name'         => $user->gym->name,
                'city'         => $user->gym->city,
                'status'       => $user->gym->status,
                'monthly_rate' => $user->gym->monthly_rate,
                'pricing'      => $user->gym->pricing,
            ];
        }

        return ApiResponse::ok(
            'login_success',
            'Welcome back!',
            $data,
        );
    }

    public function logout(Request $request): JsonResponse
    {
        // Delete only the token used in this request
        $request->user()->currentAccessToken()->delete();

        return ApiResponse::ok(
            'logged_out',
            'You have been logged out.',
        );
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Me — get the authenticated user's profile
    //
    // GET /api/auth/me
    // Header: Authorization: Bearer {token}
    // ─────────────────────────────────────────────────────────────────────────
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load('gym');

        $data = [
            'user' => [
                'id'        => $user->id,
                'name'      => $user->name,
                'phone'     => $user->phone,
                'role'      => $user->role,
                'home_city' => $user->home_city,
            ],
        ];

        if ($user->isOwner() && $user->gym) {
            $data['gym'] = [
                'id'           => $user->gym->id,
                'name'         => $user->gym->name,
                'city'         => $user->gym->city,
                'area'         => $user->gym->area,
                'status'       => $user->gym->status,
                'monthly_rate' => $user->gym->monthly_rate,
                'upi_id'       => $user->gym->upi_id,
                'pricing'      => $user->gym->pricing,
            ];
        }

        return ApiResponse::ok(
            'user_profile',
            'Profile fetched successfully.',
            $data,
        );
    }

    // ─────────────────────────────────────────────────────────────────────────

    private function maskPhone(string $phone): string
    {
        return substr($phone, 0, 4) . 'XXXXXX';
    }
}