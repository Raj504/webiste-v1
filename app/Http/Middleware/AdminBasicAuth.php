<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates the internal admin area (settlement payouts, etc.) behind a single
 * shared username/password from .env — not tied to the users table, since
 * there's no admin role in the app. Set ADMIN_USERNAME / ADMIN_PASSWORD.
 */
class AdminBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $validUsername = config('admin.username');
        $validPassword = config('admin.password');

        $providedUsername = $request->getUser();
        $providedPassword = $request->getPassword();

        $authenticated = $validUsername && $validPassword
            && $providedUsername !== null
            && $providedPassword !== null
            && hash_equals((string) $validUsername, $providedUsername)
            && hash_equals((string) $validPassword, $providedPassword);

        if (!$authenticated) {
            return response('Unauthorized', 401, ['WWW-Authenticate' => 'Basic realm="Admin"']);
        }

        return $next($request);
    }
}
