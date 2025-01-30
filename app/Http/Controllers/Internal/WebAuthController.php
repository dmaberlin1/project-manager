<?php

namespace App\Http\Controllers\Internal;


use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\Interfaces\AuthInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class WebAuthController extends Controller
{
    private AuthInterface $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->authService->register($request->validated());
        return redirect()->route('auth.login')->with('success', 'Регистрация успешна! Войдите в систему.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $this->authService->login($request->validated());
            return redirect()->route('dashboard')->with('success', 'Вы успешно вошли в систему.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        return redirect()->route('auth.login')->with('success', 'Вы успешно вышли из системы.');
    }

    public function showResetForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(ResetPasswordRequest $request): RedirectResponse
    {
        $status = $this->authService->sendPasswordResetLink($request->email);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function resetPassword(ResetPasswordRequest $request): RedirectResponse
    {
        $status = $this->authService->resetPassword($request->validated());

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('auth.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }

    public function showConfirmForm()
    {
        return view('auth.passwords.confirm');
    }

    public function confirmPassword(ConfirmPasswordRequest $request): RedirectResponse
    {
        if (!Auth::validate(['email' => Auth::user()->email, 'password' => $request->password])) {
            return back()->withErrors(['password' => 'Пароль неверен.']);
        }

        session()->put('auth.password_confirmed_at', time());
        return redirect()->intended();
    }

    public function showVerifyForm()
    {
        return view('auth.passwords.verify');
    }
}
