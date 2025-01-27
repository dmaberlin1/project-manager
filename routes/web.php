<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function (){
   Route::resource('projects',\App\Http\Controllers\ProjectController::class);
   Route::resource('tasks',\App\Http\Controllers\TaskController::class);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
