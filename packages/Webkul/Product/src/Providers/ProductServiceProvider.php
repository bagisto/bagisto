<?php

namespace Webkul\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Product\Providers\EventServiceProvider;
use Illuminate\Routing\Router;
use Webkul\Product\Models\Product;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->app->register(EventServiceProvider::class);

        $this->app->register(ModuleServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}