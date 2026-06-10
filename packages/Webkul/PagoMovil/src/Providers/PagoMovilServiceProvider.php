<?php

namespace Webkul\PagoMovil\Providers;

use Illuminate\Support\ServiceProvider;

class PagoMovilServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'pagomovil');
    }
    
    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/payment-methods.php', 'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}
