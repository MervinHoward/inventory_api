<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthRepository {
    public function register(array $data)  {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function login(array $data) {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password']
        ];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.'
            ]);
        }
        request()->session()->regenerate();
        $user = Auth::user();
        return response()->json([
            'message' => 'Login successfully.',
            'user' => new UserResource($user)
        ]);
    }

    public function tokenLogin(array $data) {
        if (!Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'message' => 'Login successfully.',
            'token' => $token,
            'user' => new UserResource($user)
        ]);
    }
}
