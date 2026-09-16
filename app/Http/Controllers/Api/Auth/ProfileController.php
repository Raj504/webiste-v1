<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * GET /api/auth/profile
     */
    public function show(Request $request): JsonResponse
    {
        return ApiResponse::ok('profile_fetched', 'Profile fetched.', [
            'user' => $this->format($request->user(), $request),
        ]);
    }

    /**
     * POST /api/auth/profile
     * POST (not PUT) since this accepts a file upload — PHP doesn't populate
     * $_FILES for native PUT + multipart without the _method spoofing dance.
     */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();

        $user->update($request->safe()->only(['name', 'email', 'phone', 'home_city']));

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $user->update(['avatar' => $request->file('avatar')->store('avatars', 'public')]);
        }

        return ApiResponse::ok('profile_updated', 'Profile updated.', [
            'user' => $this->format($user->fresh(), $request),
        ]);
    }

    // ── Private ───────────────────────────────────────────────────────────────

    private function format(User $user, Request $request): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'phone'      => $user->phone,
            'email'      => $user->email,
            'home_city'  => $user->home_city,
            'role'       => $user->role,
            // Absolute URL built from the actual incoming request's host — not
            // config('app.url'), which doesn't track a dynamic `php artisan serve`
            // port and would produce a broken link for the frontend (a different origin).
            'avatar_url' => $user->avatar ? $request->getSchemeAndHttpHost() . Storage::url($user->avatar) : null,
        ];
    }
}
