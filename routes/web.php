<?php

use App\Http\Controllers\Internal\TaskController;
use App\Http\Controllers\Internal\WebAuthController;
use Illuminate\Support\Facades\Route;

// 🔹 Web-маршруты для аутентификации
Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/register', [WebAuthController::class, 'register'])->name('auth.register.submit');
Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [WebAuthController::class, 'login'])->name('auth.login.submit');
Route::post('/logout', [WebAuthController::class, 'logout'])->name('auth.logout');

// 🔹 Web-маршруты для сброса пароля
Route::prefix('password')->group(function () {
    Route::get('/reset/{token}', [WebAuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/email', [WebAuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/reset', [WebAuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/confirm', [WebAuthController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('/confirm', [WebAuthController::class, 'confirmPassword'])->name('password.confirm.submit');
    Route::get('/verify', [WebAuthController::class, 'showVerifyForm'])->name('password.verify');
});

// Horizon UI (Только для админов)
Route::get('/horizon', '\Laravel\Horizon\Http\Controllers\HomeController@index')->middleware(['auth', 'admin']);


Route::middleware(['auth', 'can:manage-tasks'])->group(function () {
    Route::resource('tasks', TaskController::class);
});
