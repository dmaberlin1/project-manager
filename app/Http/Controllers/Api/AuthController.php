<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Interfaces\AuthInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }


    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->all());
        return response()->json($data);
    }


    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authService->login($request->only('email', 'password'));
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());
        return response()->json(['message' => 'Вы успешно вышли из системы']);
    }


    public function sendResetLinkEmail(ResetPasswordRequest $request)
    {
        $status = $this->authService->sendPasswordResetLink($request->email);

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }


    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }


    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = $this->authService->resetPassword($request->all());

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }


    public function confirmPassword(ConfirmPasswordRequest $request)
    {
        $message = $this->authService->confirmPassword($request->password);
        return response()->json(['message' => $message]);
    }
}
