<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);
        $token=$user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer'
        ]);
    }

    public function login(LoginRequest $request)
    {
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json(['message'=>'Неверные учетные данные'],401);
        }
        $user=User::query()->where('email',$request->email)->firstOrFail();
        $token=$user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token'=>$token,
            'token_type'=>'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Вы успешно вышли из системы']);
    }

    public function sendResetLinkEmail(ResetPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm($token)
    {
        return view('auth.reset', ['token' => $token]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function confirmPassword(ConfirmPasswordRequest $request)
    {
        return response()->json(['message' => 'Пароль подтвержден']);
    }
}
