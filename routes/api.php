<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
        Route::middleware('auth:api')->get('/me', [AuthController::class, 'me']);
    });

    Route::middleware('auth:api')->group(function () {
        Route::get('/users', function () {
            return response()->json(['message' => 'List of users']);
        });

        Route::post('/users', function (Request $request) {
            return response()->json(['message' => 'User created', 'data' => $request->all()]);
        });
    });
});
