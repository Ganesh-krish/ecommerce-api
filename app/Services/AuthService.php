<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Contracts\User as GoogleUser;

class AuthService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function register(array $data)
    {
        $customerRole = Role::where('name', 'CUSTOMER')->firstOrFail();

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $customerRole->id,
        ]);

        $token = auth('api')->login($user);

        return [
            'user' => $user->load('role'),
            'token' => $token,
        ];
    }

    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return null;
        }

        if (!Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = auth('api')->login($user);

        return [
            'user' => $user->load('role'),
            'token' => $token,
        ];
    }

    public function loginWithGoogle(GoogleUser $googleUser)
    {
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {

            $customerRole = Role::where('name', 'CUSTOMER')->firstOrFail();

            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => null,
                'google_id' => $googleUser->getId(),
                'role_id' => $customerRole->id,
            ]);
        } else {

            // If the existing account is a Google account,
            // make sure the Google ID is stored.
            if (!$user->google_id) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        }

        $token = auth('api')->login($user);

        return [
            'user' => $user->load('role'),
            'token' => $token,
        ];
    }
}
