<?php

namespace App\Services\Auth;

use App\Models\OtpCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class OtpService
{
    /**
     * OTP expiry window in seconds.
     */
    private const EXPIRY_SECONDS = 300; // 5 minutes

    /**
     * Max failed attempts before an OTP is permanently invalidated.
     */
    private const MAX_ATTEMPTS = 5;

    /**
     * How many OTP requests per phone per hour before rate-limiting.
     */
    private const MAX_SENDS_PER_HOUR = 5;

    /**
     * ─────────────────────────────────────────
     * Generate a new OTP and dispatch it.
     *
     * Returns the OtpCode model on success.
     * Throws \RuntimeException on rate-limit.
     * ─────────────────────────────────────────
     */
    public function send(string $phone, string $role): OtpCode
    {
        $this->enforceRateLimit($phone);

        // Invalidate any previous unused OTPs for this phone+role
        OtpCode::where('phone', $phone)
            ->where('role', $role)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        $code = $this->generateCode();

        $otpCode = OtpCode::create([
            'phone'      => $phone,
            'code'       => $code,
            'role'       => $role,
            'expires_at' => Carbon::now()->addSeconds(self::EXPIRY_SECONDS),
        ]);

        $this->dispatch($phone, $code);

        return $otpCode;
    }

    /**
     * ─────────────────────────────────────────
     * Verify an OTP submitted by the user.
     *
     * Returns the matching OtpCode on success.
     * Returns null on any failure.
     * ─────────────────────────────────────────
     */
    public function verify(string $phone, string $code, string $role): ?OtpCode
    {
        /** @var OtpCode|null $record */
        $record = OtpCode::where('phone', $phone)
            ->where('role', $role)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$record) {
            return null;
        }

        // Increment attempt counter on every check
        $record->increment('attempts');

        if ($record->attempts > self::MAX_ATTEMPTS) {
            // Too many wrong guesses — burn this OTP
            $record->update(['is_used' => true]);
            return null;
        }

        if (!$this->codesMatch($record->code, $code)) {
            return null;
        }

        // Mark as used so it cannot be replayed
        $record->update(['is_used' => true]);

        return $record;
    }

    // ─────────────────────────────────────────
    // Private helpers
    // ─────────────────────────────────────────

    /**
     * Generate the OTP code string.
     */
    private function generateCode(): string
    {
        // 4-digit numeric OTP to match the frontend UI
        return str_pad((string) random_int(1000, 9999), 4, '0', STR_PAD_LEFT);
    }

    /**
     * Compare OTP codes.
     *
     * DEV BYPASS:
     *   If the submitted code is '0000', it always passes in non-production.
     *   This lets frontend/QA test the full flow without a live SMS provider.
     *
     * when MSG91 is available:
     *   Remove the bypass block below. The hash_equals check is all you need.
     */
    private function codesMatch(string $stored, string $submitted): bool
    {
        // DEV BYPASS — remove this block when MSG91 is live
        if (!app()->isProduction() && $submitted === '0000') {
            Log::channel('single')->info('[OTP] Dev bypass used — bypassing OTP verification');
            return true;
        }
        // END DEV BYPASS

        // Constant-time comparison to prevent timing attacks
        return hash_equals($stored, $submitted);
    }

    /**
     * Dispatch the OTP via SMS.
     *
     * when MSG91 is available:
     *   Replace the Log::info() line with the MSG91 API call.
     *   Suggested: create App\Services\Sms\Msg91Service and inject it here.
     *
     *   Example MSG91 call:
     *     $this->msg91->sendOtp($phone, $code);
     */
    private function dispatch(string $phone, string $code): void
    {
        // when MSG91 is available: replace with real SMS dispatch
        Log::channel('single')->info("[OTP] Dev mode — OTP for {$phone}: {$code}");
    }

    /**
     * Throw if this phone has exceeded the send limit in the last hour.
     */
    private function enforceRateLimit(string $phone): void
    {
        $recent = OtpCode::where('phone', $phone)
            ->where('created_at', '>', Carbon::now()->subHour())
            ->count();

        if ($recent >= self::MAX_SENDS_PER_HOUR) {
            throw new \RuntimeException(
                "Too many OTP requests. Please wait before requesting another code."
            );
        }
    }
}