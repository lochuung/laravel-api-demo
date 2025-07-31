<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\DashboardServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

    private const SERVICES = [
        AuthServiceInterface::class => AuthService::class,
        DashboardServiceInterface::class => \App\Services\DashboardService::class,
        UserServiceInterface::class => UserService::class,
        ProductServiceInterface::class => ProductService::class,
        OrderServiceInterface::class => OrderService::class
    ];

    public function register(): void
    {
        //
        // Register the services
        foreach (self::SERVICES as $interface => $service) {
            $this->app->bind($interface, $service);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
