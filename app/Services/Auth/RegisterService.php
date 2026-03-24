<?php

namespace App\Services\Auth;

use App\Models\Gym;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\NewAccessToken;

class RegisterService
{
    /**
     * ─────────────────────────────────────────
     * Register a traveler.
     *
     * Creates a user with role='traveler'.
     * Returns the User and a Sanctum access token.
     * ─────────────────────────────────────────
     *
     * @return array{user: User, token: NewAccessToken}
     * @throws \RuntimeException if phone is already registered
     */
    public function registerTraveler(string $phone, array $data): array
    {
        $this->assertPhoneNotTaken($phone, 'traveler');

        $user = DB::transaction(function () use ($phone, $data) {
            return User::create([
                'role'      => 'traveler',
                'phone'     => $phone,
                'name'      => trim($data['first_name'] . ' ' . $data['last_name']),
                'home_city' => $data['home_city'] ?? null,
            ]);
        });

        $token = $user->createToken('mobile', ['role:traveler']);

        return compact('user', 'token');
    }

    /**
     * ─────────────────────────────────────────
     * Register a gym owner.
     *
     * Creates a user with role='owner' and a linked Gym record.
     * Returns the User, Gym, and a Sanctum access token.
     * ─────────────────────────────────────────
     *
     * @return array{user: User, gym: Gym, token: NewAccessToken}
     * @throws \RuntimeException if phone is already registered
     */
    public function registerOwner(string $phone, array $data): array
    {
        $this->assertPhoneNotTaken($phone, 'owner');

        ['user' => $user, 'gym' => $gym] = DB::transaction(function () use ($phone, $data) {
            $user = User::create([
                'role'  => 'owner',
                'phone' => $phone,
                'name'  => $data['owner_name'],
            ]);

            $gym = Gym::create([
                'user_id'      => $user->id,
                'name'         => $data['gym_name'],
                'address_text' => $data['address_text'],
                'lat'          => $data['lat'],
                'lng'          => $data['lng'],
                'city'         => $data['city'] ?? null,
                'area'         => $data['area'] ?? null,
                'monthly_rate' => (int) $data['monthly_rate'],
                'upi_id'       => $data['upi_id'] ?? null,
                'status'       => 'active', 
                'mapbox_place_id' => $data['mapbox_place_id'] ?? null,
            ]);

            return compact('user', 'gym');
        });

        $token = $user->createToken('mobile', ['role:owner']);

        return compact('user', 'gym', 'token');
    }

    // ─────────────────────────────────────────

    /**
     * Throws if a user with this phone+role combo already exists.
     * A phone number can technically be both a traveler and an owner
     * (two separate accounts) — so we scope the check to role.
     */
    private function assertPhoneNotTaken(string $phone, string $role): void
    {
        $exists = User::where('phone', $phone)
            ->where('role', $role)
            ->exists();

        if ($exists) {
            throw new \RuntimeException(
                "This phone number is already registered as a {$role}."
            );
        }
    }
}