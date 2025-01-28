<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;
use Laravel\Horizon\Http\Controllers\HomeController;


// Открытые маршруты для регистрации и входа
Route::get('/register', [AuthController::class, 'showRegisterForm']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Маршруты для сброса пароля
Route::prefix('password')->group(function (){
    Route::get('/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/email', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/reset', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::post('/confirm', [AuthController::class, 'confirmPassword'])->middleware('auth:sanctum');
});


Route::middleware('auth:sanctum')->group(function () {
    // Логика выхода из системы
    Route::post('/logout', [AuthController::class, 'logout']);

    // Статистика по задачам
    Route::prefix('statistics')->group(function (){
        Route::get('/tasks-status/{projectId}', [StatisticsController::class, 'taskStatusCount']);
        Route::get('/average-completion/{projectId}', [StatisticsController::class, 'averageTaskCompletionTime']);
        Route::get('/top-users', [StatisticsController::class, 'topActiveUsers']);
        Route::get('/export/{projectId}', [StatisticsController::class, 'exportTaskStatusToCsv']);
    });

    // Маршруты для проектов и задач
    Route::resource('projects', ProjectController::class);
    Route::resource('tasks', TaskController::class);

    // Отправка уведомлений
    Route::post('/notifications/send', [NotificationController::class, 'sendNotifications']);

    // Удаление проектов с задержкой
    Route::delete('/projects/{id}/delayed', [ProjectController::class, 'deleteProjectDelayed']);
});

// Horizon с дополнительным middleware для админов
Route::get('/horizon', [HomeController::class, 'index'])->middleware(['auth:sanctum', 'admin']);

// Открытые маршруты для погоды и GitHub
Route::get('/weather/{location}', [WeatherController::class, 'show']);
Route::get('/github/{username}', [GitHubController::class, 'show']);
