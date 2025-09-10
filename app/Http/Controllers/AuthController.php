<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService) {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request) {
        $user = $this->authService->register($request->validated());
        return response()->json([
            'message' => 'User registered successfully.',
            'data' => $user
        ]);
    }

    public function login(LoginRequest $request) {
        return $this->authService->login($request->validated());
    }

    public function tokenLogin(LoginRequest $request) {
        return $this->authService->tokenLogin($request->validated());
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    public function user(Request $request) {
        return response()->json(new UserResource($request->user()));
    }
}
