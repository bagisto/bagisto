<?php

namespace Webkul\StripeConnect\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class StripeConnectServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/routes.php';

        $this->publishes([
            dirname(__DIR__) . '/Resources/assets/sass/stripe.scss' => base_path('public/vendor/webkul/stripe/assets/css/stripe.css')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/assets/images/' => base_path('public/vendor/webkul/stripe/assets/images/')
        ]);

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'stripe');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'stripe');

        $this->app->register(EventServiceProvider::class);
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
     * Merge the stripe connect's configuration with the admin panel
     */
    public function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/paymentmethods.php', 'paymentmethods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}