<?php

use App\Exceptions\ExceptionRegistrar;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

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
//            EnsureFrontendRequestsAreStateful::class,
//            'throttle:api',
            SubstituteBindings::class,
        ]);
    })
    ->withExceptions(
        fn(Exceptions $exceptions) => app(ExceptionRegistrar::class)->handle($exceptions)
    )->create();
