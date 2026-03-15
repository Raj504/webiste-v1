<?php

namespace App\Services\Auth;

use App\Models\User;

class LoginService
{
    /**
     * Find an existing user by phone + role.
     *
     * Returns null if the user does not exist — caller decides
     * whether to suggest signup or return a 404.
     */
    public function findUser(string $phone, string $role): ?User
    {
        return User::where('phone', $phone)
            ->where('role', $role)
            ->first();
    }

    /**
     * Issue a fresh Sanctum token for a returning user.
     *
     * We revoke all previous tokens for the same device ability
     * so the user is never double-logged-in on the same role.
     *
     * @return array{user: User, token: \Laravel\Sanctum\NewAccessToken}
     */
    public function loginUser(User $user): array
    {
        // Revoke existing tokens for this role so old devices log out cleanly
        $user->tokens()->where('name', 'mobile')->delete();

        $token = $user->createToken('mobile', ["role:{$user->role}"]);

        return compact('user', 'token');
    }
}