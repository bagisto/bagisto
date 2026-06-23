<?php

namespace Webkul\PayU\Providers;

use Illuminate\Support\ServiceProvider;

class PayUServiceProvider extends ServiceProvider
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

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'payu');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'payu');
    }

    /**
     * Merge the PayU configuration with payment methods
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/payment-methods.php', 'payment_methods'
        );
    }
}
