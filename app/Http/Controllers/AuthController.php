<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;


class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // 2. Find CUSTOMER role
        $customerRole = Role::where('name', 'CUSTOMER')->firstOrFail();

        // 3. Create user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $customerRole->id,
        ]);

        // 4. Generate JWT
        $token = auth('api')->login($user);

        // 5. Return response
        return response()->json([
            'message' => 'Registration successful',
            'token' => $token,
            'user' => new UserResource($user->load('role')),
        ], 201);
    }

    public function login(LoginRequest $request){
        $data = $request->validated();

         $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        if (!Hash::check($data['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        $token = auth('api')->login($user);

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => new UserResource($user->load('role')),
        ]);
    }
}