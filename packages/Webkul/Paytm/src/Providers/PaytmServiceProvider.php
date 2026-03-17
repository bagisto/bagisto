<?php

namespace Webkul\Paytm\Providers;

use Illuminate\Support\ServiceProvider;

class PaytmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/paymentmethods.php',
            'payment_methods'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/frount-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'paytm');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'paytm');

        $this->app->register(EventServiceProvider::class);
    }
}
