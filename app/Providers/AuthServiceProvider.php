<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\User;
use App\Policies\ProductPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{

    private const POLICIES = [
        User::class => UserPolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        foreach (self::POLICIES as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Gate for Admin-only actions
        Gate::define('is-admin', function (User $user) {
            return $user->role === User::ROLE_ADMIN;
        });

        // Gate for Moderator or Admin
        Gate::define('is-moderator', function (User $user) {
            return in_array($user->role, [
                User::ROLE_ADMIN,
                User::ROLE_MODERATOR
            ]);
        });

        // Gate for all logged-in users
        Gate::define('is-user', function (User $user) {
            return in_array($user->role, [
                User::ROLE_ADMIN,
                User::ROLE_MODERATOR,
                User::ROLE_USER
            ]);
        });
    }
}
