<?php

namespace App\Http\Controllers\v1\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\auth\LoginRequest;
use App\Http\Requests\v1\auth\RegisterUserRequest;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $user = User::create($request->validated());

        $user->notify(new WelcomeNotification());

        return response()->json([
            'message' => 'Registration successful',
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }
}
