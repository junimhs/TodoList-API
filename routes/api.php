<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TodoTaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function() {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify-email', [AuthController::class, 'verify_email']);
    Route::post('forgot-password', [AuthController::class, 'forgot_password']);
    Route::post('reset-password', [AuthController::class, 'reset_password']);

    Route::prefix('me')->group(function () {
        Route::get('', [MeController::class, 'index']);
        Route::put('', [MeController::class, 'update']);
    });

    Route::prefix('todos')->group(function() {
        Route::get('', [TodoController::class, 'index']);
        Route::get('{todo}', [TodoController::class, 'show']);
        Route::post('', [TodoController::class, 'store']);
        Route::put('{todo}', [TodoController::class, 'update']);
        Route::delete('{todo}', [TodoController::class, 'destroy']);

        // Todo Task
        Route::post('{todo}/tasks', [TodoTaskController::class, 'store']);
    });

    Route::prefix('todo-task')->group(function() {
        Route::put('{todoTask}', [TodoTaskController::class, 'update']);
        Route::delete('{todoTask}', [TodoTaskController::class, 'destroy']);
    });
});
