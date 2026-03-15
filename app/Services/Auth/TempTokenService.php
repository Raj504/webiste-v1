<?php

namespace App\Services\Auth;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Issues a short-lived token that proves "this phone+role passed OTP".
 *
 * Why:
 *   After OTP verification, the client needs to send more data (name, gym info)
 *   before a user record is created. We can't issue a Sanctum token yet because
 *   there is no user. We can't trust the phone again because the OTP is burned.
 *   So we issue a temp token, store it in cache, and consume it in /register.
 *
 * Lifetime: 15 minutes — enough for the user to fill the remaining form steps.
 */
class TempTokenService
{
    private const TTL_MINUTES = 15;
    private const CACHE_PREFIX = 'gympass_temp_token:';

    /**
     * Issue a new temp token for a verified phone+role pair.
     */
    public function issue(string $phone, string $role): string
    {
        $token = Str::random(64);

        Cache::put(
            self::CACHE_PREFIX . $token,
            ['phone' => $phone, 'role' => $role],
            now()->addMinutes(self::TTL_MINUTES)
        );

        return $token;
    }

    /**
     * Consume a temp token.
     *
     * Returns ['phone' => ..., 'role' => ...] on success, null otherwise.
     * Consuming removes the token — it cannot be reused.
     */
    public function consume(string $token): ?array
    {
        $key = self::CACHE_PREFIX . $token;

        $payload = Cache::get($key);

        if (!$payload) {
            return null;
        }

        Cache::forget($key);

        return $payload;
    }
}