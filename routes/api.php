<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MeController;
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
});
