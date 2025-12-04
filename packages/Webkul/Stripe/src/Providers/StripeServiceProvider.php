<?php

namespace Webkul\Stripe\Providers;

use Illuminate\Support\ServiceProvider;

class StripeServiceProvider extends ServiceProvider
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

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'stripe');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'stripe');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'stripe');
    }

    /**
     * Merge the stripe connect's configuration with the admin panel
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/payment-methods.php', 'payment_methods'
        );
    }
}
