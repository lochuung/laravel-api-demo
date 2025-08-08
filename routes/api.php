<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\InventoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductUnitController;
use App\Http\Controllers\Api\V1\UserController;
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
        Route::middleware('auth:api')->get('/me', [AuthController::class, 'getMyProfile']);
    });


    Route::middleware('auth:api')->group(function () {

        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        Route::post('upload/image', [\App\Http\Controllers\Api\V1\UploadController::class, 'uploadImage'])
            ->name('upload.image');

        Route::apiResource('users', UserController::class);

        Route::get('users/{id}/orders', [UserController::class, 'showWithOrders'])
            ->name('users.showWithOrders');

        Route::get('products/filter-options', [ProductController::class, 'filterOptions'])
            ->name('products.filterOptions');
        Route::apiResource('products', ProductController::class);

        // Product Unit routes with nested and standalone structure
        Route::get('products/{product}/units', [ProductUnitController::class, 'index'])
            ->name('products.units.index');
        Route::post('products/{product}/units', [ProductUnitController::class, 'store'])
            ->name('products.units.store');
        Route::get('products/{product}/units/{unit}', [ProductUnitController::class, 'show'])
            ->name('products.units.show');
        Route::put('products/units/{unit}', [ProductUnitController::class, 'update'])
            ->name('products.units.update');
        Route::delete('products/units/{unit}', [ProductUnitController::class, 'destroy'])
            ->name('products.units.destroy');

        // Inventory routes
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('stats', [InventoryController::class, 'stats'])->name('stats');
            Route::post('import', [InventoryController::class, 'import'])->name('import');
            Route::post('export', [InventoryController::class, 'export'])->name('export');
            Route::post('adjust', [InventoryController::class, 'adjust'])->name('adjust');
            Route::get('products/{product}/history', [InventoryController::class, 'productHistory'])->name('product.history');
        });

        Route::apiResource('orders', OrderController::class);
    });
});
