<?php

namespace Webkul\Razorpay\Providers;

use Illuminate\Support\ServiceProvider;

class RazorpayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'razorpay');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'razorpay');
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/paymentmethods.php',
            'payment_methods'
        );
    }
}
