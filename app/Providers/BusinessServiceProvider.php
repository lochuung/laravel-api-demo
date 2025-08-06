<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\DashboardServiceInterface;
use App\Services\Contracts\InventoryServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\ProductUnitServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\DashboardService;
use App\Services\InventoryService;
use App\Services\OrderService;
use App\Services\ProductService;
use App\Services\ProductUnitService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class BusinessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

    private const SERVICES = [
        AuthServiceInterface::class => AuthService::class,
        DashboardServiceInterface::class => DashboardService::class,
        UserServiceInterface::class => UserService::class,
        ProductServiceInterface::class => ProductService::class,
        ProductUnitServiceInterface::class => ProductUnitService::class,
        OrderServiceInterface::class => OrderService::class,
        InventoryServiceInterface::class => InventoryService::class,
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
