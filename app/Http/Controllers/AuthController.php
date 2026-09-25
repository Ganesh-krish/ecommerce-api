<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;

class AuthController extends Controller
{

    public function __construct(
        private AuthService $authService
    ) {
        }
    
    public function register(RegisterRequest $request)
    {
    $result = $this->authService->register(
        $request->validated()
    );

    return response()->json([
        'message' => 'Registration successful',
        'token' => $result['token'],
        'user' => new UserResource($result['user']),
    ], 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            $request->validated()
        );

        if (!$result) {
            return response()->json([
                'message' => 'Invalid email or password',
            ], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $result['token'],
            'user' => new UserResource($result['user']),
        ]);
    }

    public function me()
    {
        $user = auth('api')->user();

        return new UserResource($user->load('role'));
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}