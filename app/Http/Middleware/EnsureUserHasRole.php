<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;

/**
 * Ensures the authenticated user has the required role.
 *
 * Usage in routes: ->middleware('role:owner')
 * Registered in Kernel.php $routeMiddleware as 'role'
 */
class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!$request->user() || $request->user()->role !== $role) {
            return ApiResponse::unauthorized(
                'forbidden',
                'You do not have permission to access this resource.'
            );
        }
    
        return $next($request);
    }
}