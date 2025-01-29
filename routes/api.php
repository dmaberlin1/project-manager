<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/user', static function (Request $request) {
            return $request->user();
        });

        Route::resource('projects', ProjectController::class);
        Route::resource('tasks', TaskController::class);
    });
});
