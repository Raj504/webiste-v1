<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * These exceptions are never reported (no log noise for expected client errors).
     */
    protected $dontReport = [
        //
    ];

    /**
     * These inputs are never flashed to session on validation error.
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Override render to ensure every API response goes through
     * the ApiResponse envelope — never raw Laravel HTML or default JSON.
     */
    public function render($request, Throwable $e)
    {
        // Only intercept routes under /api/*
        if (!$request->is('api/*')) {
            return parent::render($request, $e);
        }

        // ── Validation failure (FormRequest) ────────────────────────────────
        // Laravel throws this when FormRequest rules fail.
        // We flatten all field errors into { field: [messages] }.
        if ($e instanceof ValidationException) {
            return ApiResponse::validationError($e->errors());
        }

        // ── Unauthenticated (missing or invalid Sanctum token) ───────────────
        // Thrown by Sanctum middleware when no valid Bearer token is provided.
        if ($e instanceof AuthenticationException) {
            return ApiResponse::unauthorized(
                'unauthenticated',
                'Authentication required. Please log in.',
            );
        }

        // ── Route not found ──────────────────────────────────────────────────
        if ($e instanceof NotFoundHttpException) {
            return ApiResponse::badRequest(
                'route_not_found',
                'The requested endpoint does not exist.',
            );
        }

        // ── Wrong HTTP method ────────────────────────────────────────────────
        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponse::badRequest(
                'method_not_allowed',
                'HTTP method not allowed for this endpoint.',
            );
        }

        // ── Catch-all: unexpected server error ───────────────────────────────
        // Log it, but never expose the raw exception message to the client.
        report($e);

        return ApiResponse::serverError();
    }
}