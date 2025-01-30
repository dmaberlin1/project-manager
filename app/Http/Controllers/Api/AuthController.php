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
    private AuthInterface $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request)
    {
        $data = $this->authService->register($request->validated());
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
        return response()->json(['message' => __($status)], $status === Password::RESET_LINK_SENT ? 200 : 400);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = $this->authService->resetPassword($request->validated());

        return response()->json([
            'message' => __($status)
        ], $status === Password::PASSWORD_RESET ? 200 : 400);
    }

    public function confirmPassword(ConfirmPasswordRequest $request)
    {
        $message = $this->authService->confirmPassword($request->password);
        return response()->json(['message' => $message]);
    }
}
