<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function () {
    Route::prefix("auth")->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
    });

    Route::get('/users', function () {
        return response()->json(['message' => 'List of users']);
    })->middleware('auth:sanctum');

    Route::post('/users', function (Request $request) {
        return response()->json(['message' => 'User created', 'data' => $request->all()]);
    })->middleware('auth:sanctum');
});
