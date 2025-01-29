<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\External\GitHubController;
use App\Http\Controllers\External\WeatherController;
use App\Http\Controllers\Internal\NotificationController;
use App\Http\Controllers\Internal\ProjectController;
use App\Http\Controllers\Internal\TaskController;
use Illuminate\Support\Facades\Route;
use Laravel\Horizon\Http\Controllers\HomeController;

// Открытые маршруты для регистрации и входа
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.submit');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// Маршруты для сброса пароля
Route::prefix('password')->group(function () {
    Route::get('/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/reset', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::post('/confirm', [AuthController::class, 'confirmPassword'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
// Логика выхода из системы
    Route::post('/logout', [AuthController::class, 'logout']);

// Статистика по задачам
    Route::prefix('statistics')->group(function () {
        Route::get('/tasks-status/{projectId}', [StatisticsController::class, 'taskStatusCount']);
        Route::get('/average-completion/{projectId}', [StatisticsController::class, 'averageTaskCompletionTime']);
        Route::get('/top-users', [StatisticsController::class, 'topActiveUsers']);

        Route::get('/tasks/export/csv/{projectId}', [TaskController::class, 'exportCsv'])
            ->name('tasks.export.csv')->whereNumber('projectId');
        Route::get('/tasks/export/json/{projectId}', [TaskController::class, 'exportJson'])
            ->name('tasks.export.json')->whereNumber('projectId');
    });

// Маршруты для проектов и задач
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);

// Отправка уведомлений
    Route::post('/notifications/send', [NotificationController::class, 'sendNotifications']);

// Удаление проектов с задержкой
    Route::delete('/projects/{id}/delayed', [ProjectController::class, 'deleteProjectDelayed'])->whereNumber('id');
});

// Horizon с дополнительным middleware для админов
Route::get('/horizon', [HomeController::class, 'index'])->middleware(['auth:sanctum', 'admin']);

// Открытые маршруты для погоды и GitHub
Route::get('/weather/{location}', [WeatherController::class, 'show'])->where('location', '[A-Za-z]+');
Route::get('/github/{username}', [GitHubController::class, 'show'])->where('username', '[A-Za-z0-9\-]+');
