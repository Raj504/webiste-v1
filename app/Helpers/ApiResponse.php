<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

/**
 * Centralised response envelope.
 *
 * Every response has the same shape:
 * {
 *   "success": true|false,
 *   "code":    "snake_case_semantic_code",
 *   "message": "Human readable message",
 *   "data":    { ... } | null
 * }
 *
 * Frontend can always key on `code` for logic, `message` for display.
 */
class ApiResponse
{
    /**
     * 200 — success with data
     */
    public static function ok(string $code, string $message, array $data = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data ?: null,
        ], 200);
    }

    /**
     * 201 — resource created
     */
    public static function created(string $code, string $message, array $data = []): JsonResponse
    {
        return response()->json([
            'success' => true,
            'code'    => $code,
            'message' => $message,
            'data'    => $data ?: null,
        ], 201);
    }

    /**
     * 400 — client sent bad data (validation already handled by FormRequest,
     *       this is for business-logic failures)
     */
    public static function badRequest(string $code, string $message, array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors ?: null,
        ], 400);
    }

    /**
     * 401 — unauthenticated / OTP mismatch
     */
    public static function unauthorized(string $code, string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => null,
        ], 401);
    }

    /**
     * 409 — conflict (phone already registered, etc.)
     */
    public static function conflict(string $code, string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => null,
        ], 409);
    }

    /**
     * 422 — Laravel validation failure (used in Handler or FormRequest)
     */
    public static function validationError(array $errors): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => 'validation_error',
            'message' => 'The given data was invalid.',
            'errors'  => $errors,
        ], 422);
    }

    /**
     * 429 — rate limit / too many OTP requests
     */
    public static function tooManyRequests(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => 'rate_limited',
            'message' => $message,
            'errors'  => null,
        ], 429);
    }

    /**
     * 500 — unexpected server error
     */
    public static function serverError(string $message = 'Something went wrong. Please try again.'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'code'    => 'server_error',
            'message' => $message,
            'errors'  => null,
        ], 500);
    }
}