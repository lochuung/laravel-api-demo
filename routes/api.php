<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::post('/verify-email', [AuthController::class, 'verifyEmail']);
        Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
        Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
        Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);
    });


    Route::middleware('auth:api')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::apiResource('users', UserController::class);

        Route::apiResource('products', ProductController::class);

        Route::apiResource('orders', OrderController::class);
    });
});
