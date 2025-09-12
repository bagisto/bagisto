<?php

namespace Webkul\TwoFactorAuth\Providers;

use Illuminate\Support\ServiceProvider;

class TwoFactorAuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__.'/../Routes/admin-routes.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'two_factor_auth');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'two_factor_auth');

    }

    /**
     * Register services.
     */
    public function register(): void {}
}
