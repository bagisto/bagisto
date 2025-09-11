<?php

namespace Webkul\TwoFactorAuth\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

use Illuminate\Support\Facades\Config;

class TwoFactorAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Routes/admin-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'two_factor_auth');

    }

}
