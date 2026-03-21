<?php

namespace Webkul\PhonePe\Providers;

use Illuminate\Support\ServiceProvider;

class PhonePeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'phonepe');
    }

    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/payment_methods.php',
            'payment_methods'
        );
    }
}