<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Internal\NotificationController;
use App\Http\Controllers\Internal\ProjectController;
use App\Http\Controllers\Internal\TaskController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    // 🔹 Открытые API-маршруты (регистрация, вход)
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register'])->name('api.auth.register');
        Route::post('/login', [AuthController::class, 'login'])->name('api.auth.login');

        // 🔹 Сброс пароля
        Route::prefix('password')->group(function () {
            Route::post('/email', [AuthController::class, 'sendResetLinkEmail'])->name('api.password.email');
            Route::post('/reset', [AuthController::class, 'resetPassword'])->name('api.password.update');
            Route::post('/confirm', [AuthController::class, 'confirmPassword'])->middleware('auth:sanctum');
        });
    });

    // 🔹 Закрытые API-маршруты (нужна аутентификация)
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('api.auth.logout');

        // 📊 API-маршруты статистики
        Route::prefix('statistics')->group(function () {
            Route::get('/tasks-status/{projectId}', [StatisticsController::class, 'taskStatusCount']);
            Route::get('/average-completion/{projectId}', [StatisticsController::class, 'averageTaskCompletionTime']);
            Route::get('/top-users', [StatisticsController::class, 'topActiveUsers']);

            // 📥 Экспорт задач
            Route::get('/tasks/export/csv/{projectId}', [TaskController::class, 'exportCsv'])->whereNumber('projectId');
            Route::get('/tasks/export/json/{projectId}', [TaskController::class, 'exportJson'])->whereNumber('projectId');
        });

        // 📌 API для проектов и задач
        Route::apiResource('projects', ProjectController::class);
        Route::apiResource('tasks', TaskController::class);

        // 📢 API для уведомлений
        Route::post('/notifications/send', [NotificationController::class, 'sendNotifications']);

        // ⏳ API для удаления проектов с задержкой
        Route::delete('/projects/{id}/delayed', [ProjectController::class, 'deleteProjectDelayed'])->whereNumber('id');
    });

    // Horizon API (только для админов)
    Route::get('/horizon', function () {
        return response()->json(['message' => 'Horizon доступен только для администраторов.']);
    })->middleware(['auth:sanctum', 'admin']);
});
