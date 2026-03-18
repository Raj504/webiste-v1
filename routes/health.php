<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

// ─────────────────────────────────────────────────────────────────────────────
// Health Check
//
// GET /api/health        → quick ping (frontend connectivity test)
// GET /api/health/full   → detailed status (for debugging / team dashboard)
// ─────────────────────────────────────────────────────────────────────────────

// Quick ping — lightweight, no DB hit
// Frontend should use this for simple "is the API up?" checks
Route::get('/health', function () {
    return response()->json([
        'status'    => 'ok',
        'timestamp' => now()->toISOString(),
        'env'       => app()->environment(),
    ], 200);
});

// Full status — DB check + stats
// Use this for debugging or team dashboards, not for frequent polling
Route::get('/health/full', function () {

    // ── Database check ───────────────────────────────────────────────────────
    $dbStatus  = 'ok';
    $dbMessage = null;
    try {
        DB::connection()->getPdo();
    } catch (\Exception $e) {
        $dbStatus  = 'error';
        $dbMessage = 'Cannot connect to database';
    }

    // ── User stats ───────────────────────────────────────────────────────────
    $userStats = [];
    if ($dbStatus === 'ok') {
        try {
            $userStats = [
                'total_users'     => \App\Models\User::count(),
                'total_travelers' => \App\Models\User::where('role', 'traveler')->count(),
                'total_owners'    => \App\Models\User::where('role', 'owner')->count(),
                'new_today'       => \App\Models\User::whereDate('created_at', today())->count(),
                'new_this_week'   => \App\Models\User::where('created_at', '>=', now()->startOfWeek())->count(),
            ];
        } catch (\Exception $e) {
            $userStats = ['error' => 'Could not fetch user stats'];
        }
    }

    // ── Gym stats ────────────────────────────────────────────────────────────
    $gymStats = [];
    if ($dbStatus === 'ok') {
        try {
            $gymStats = [
                'total_gyms'   => \App\Models\Gym::count(),
                'active_gyms'  => \App\Models\Gym::where('status', 'active')->count(),
                'pending_gyms' => \App\Models\Gym::where('status', 'pending')->count(),
            ];
        } catch (\Exception $e) {
            $gymStats = ['error' => 'Could not fetch gym stats'];
        }
    }

    // ── OTP activity ─────────────────────────────────────────────────────────
    $otpStats = [];
    if ($dbStatus === 'ok') {
        try {
            $otpStats = [
                'sent_today'       => \App\Models\OtpCode::whereDate('created_at', today())->count(),
                'sent_this_hour'   => \App\Models\OtpCode::where('created_at', '>=', now()->subHour())->count(),
                'verified_today'   => \App\Models\OtpCode::whereDate('created_at', today())->where('is_used', true)->count(),
            ];
        } catch (\Exception $e) {
            $otpStats = ['error' => 'Could not fetch OTP stats'];
        }
    }

    // ── Routes list ──────────────────────────────────────────────────────────
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri'    => $route->uri(),
            'name'   => $route->getName(),
        ];
    })->filter(function ($route) {
        // Only show API routes
        return str_starts_with($route['uri'], 'api/');
    })->values();

    // ── Storage check ────────────────────────────────────────────────────────
    $storageWritable = is_writable(storage_path());

    // ── Response ─────────────────────────────────────────────────────────────
    $overallStatus = $dbStatus === 'ok' ? 'ok' : 'degraded';

    return response()->json([
        'status'    => $overallStatus,
        'timestamp' => now()->toISOString(),
        'env'       => app()->environment(),

        'services' => [
            'database' => [
                'status'  => $dbStatus,
                'message' => $dbMessage,
            ],
            'storage' => [
                'status' => $storageWritable ? 'ok' : 'error',
            ],
        ],

        'stats' => [
            'users' => $userStats,
            'gyms'  => $gymStats,
            'otp'   => $otpStats,
        ],

        'routes' => $routes,

        'app' => [
            'name'       => config('app.name'),
            'url'        => config('app.url'),
            'debug'      => config('app.debug'),
            'php'        => PHP_VERSION,
            'laravel'    => app()->version(),
            'timezone'   => config('app.timezone'),
        ],
    ], $overallStatus === 'ok' ? 200 : 503);
});