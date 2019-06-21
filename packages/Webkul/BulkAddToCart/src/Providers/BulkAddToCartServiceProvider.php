<?php

namespace Webkul\BulkAddToCart\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class BulkAddToCartServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'bulkaddtocart');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'bulkaddtocart');
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
            dirname(__DIR__) . '/Config/menu.php', 'menu.customer'
        );
    }
}