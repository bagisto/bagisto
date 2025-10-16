<?php

namespace Webkul\Razorpay\Providers;

use Illuminate\Support\ServiceProvider;

class RazorpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include(__DIR__ . '/../Routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'razorpay');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'razorpay');

        $this->publishes([
            __DIR__ .'/../../publishable/build' => public_path('themes/razorpay/build')
        ], 'public');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
       $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php', 
            'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/vite.php',
            'bagisto-vite.viters'
        );
    }
}