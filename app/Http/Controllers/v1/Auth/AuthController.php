<?php

namespace App\Http\Controllers\v1\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Http\Requests\v1\auth\LoginRequest;
use App\Notifications\v1\WelcomeNotification;
use App\Http\Requests\v1\auth\RegisterUserRequest;
use App\Http\Requests\v1\Auth\ResetPasswordRequest;
use App\Http\Requests\v1\Auth\ChangePasswordRequest;
use App\Http\Requests\v1\Auth\ForgotPasswordRequest;

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
    public function forgotPassword(ForgotPasswordRequest $request)
{
    $status = Password::sendResetLink(
        $request->only('email')
    );

    if ($status === Password::RESET_LINK_SENT) {
        return response()->json([
            'message' => __($status)
        ]);
    }

    return response()->json([
        'message' => __($status)
    ], 400);
}

public function resetPassword(ResetPasswordRequest $request)
{
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            event(new PasswordReset($user));
        }
    );

    if ($status == Password::PASSWORD_RESET) {
        return response()->json([
            'message' => __($status)
        ]);
    }

    return response()->json([
        'message' => __($status)
    ], 400);
}

public function changePassword(ChangePasswordRequest $request)
{
    $user = $request->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return response()->json([
            'message' => 'Current password is incorrect'
        ], 400);
    }

    $user->password = bcrypt($request->password);
    $user->save();

    return response()->json([
        'message' => 'Password changed successfully'
    ]);
}

}
