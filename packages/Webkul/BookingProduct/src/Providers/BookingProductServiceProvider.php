<?php

namespace Webkul\BookingProduct\Providers;

use Illuminate\Support\ServiceProvider;

class BookingProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Http/front-routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'booking');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'booking');

        $this->app->register(EventServiceProvider::class);

        $this->app->register(ModuleServiceProvider::class);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/product_types.php', 'product_types');

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );
    }
}
