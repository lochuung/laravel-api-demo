<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // add sanctum middleware
        $middleware->api(
            'api',
            [
                'throttle:api',
                'bindings',
                'auth:sanctum',
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->renderable(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                // Log lỗi
                \Log::error('API Error', [
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);

                return response()->json([
                    'message' => 'Internal Server Error',
                    'error' => config('app.debug') ? $e->getMessage() : null,
                ], 500);
            }

            return null; // fallback về mặc định nếu không phải API
        });

        // Xử lý lỗi validation riêng (422)
        $exceptions->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Xử lý lỗi không tìm thấy model
        $exceptions->renderable(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Resource not found',
                ], 404);
            }
        });

        // Xử lý lỗi route không tồn tại
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Endpoint not found',
                ], 404);
            }
        });
    })->create();
