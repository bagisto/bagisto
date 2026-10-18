<?php

namespace Webkul\Cashfree\Providers;

use Illuminate\Support\ServiceProvider;

class CashfreeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'cashfree');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'cashfree');
    }

    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/payment-methods.php',
            'payment_methods'
        );
    }
}
