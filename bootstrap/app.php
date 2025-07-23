<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // API Middleware
        $middleware->api(prepend: [
            EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Authentication Exception (401)
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                \Log::warning('Authentication failed', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'error_code' => 'UNAUTHENTICATED'
                ], 401);
            }
        });

        // Authorization Exception (403)
        $exceptions->renderable(function (AuthorizationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                \Log::warning('Authorization failed', [
                    'user_id' => auth()->id(),
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Forbidden',
                    'error_code' => 'FORBIDDEN'
                ], 403);
            }
        });

        // Validation Exception (422)
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                \Log::info('Validation failed', [
                    'errors' => $e->errors(),
                    'input' => $request->except(['password', 'password_confirmation']),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                    'error_code' => 'VALIDATION_FAILED'
                ], 422);
            }
        });

        // Model Not Found Exception (404)
        $exceptions->renderable(function (ModelNotFoundException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                \Log::info('Resource not found', [
                    'model' => $e->getModel(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                    'error_code' => 'RESOURCE_NOT_FOUND'
                ], 404);
            }
        });

        // Route Not Found Exception (404)
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                \Log::info('Endpoint not found', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Endpoint not found',
                    'error_code' => 'ENDPOINT_NOT_FOUND'
                ], 404);
            }
        });

        // Method Not Allowed Exception (405)
        $exceptions->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                \Log::info('Method not allowed', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'allowed_methods' => $e->getHeaders()['Allow'] ?? 'Unknown',
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Method not allowed',
                    'error_code' => 'METHOD_NOT_ALLOWED',
                    'allowed_methods' => $e->getHeaders()['Allow'] ?? null
                ], 405);
            }
        });

        // General Exception Handler (500)
        $exceptions->renderable(function (Throwable $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                // Log chi tiết lỗi
                \Log::error('API Error', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_id' => auth()->id(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'input' => $request->except(['password', 'password_confirmation']),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Internal Server Error',
                    'error_code' => 'INTERNAL_ERROR',
                    'debug' => config('app.debug') ? [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => explode("\n", $e->getTraceAsString())
                    ] : null,
                ], 500);
            }

            return null; // fallback về mặc định nếu không phải API
        });

    })->create();
