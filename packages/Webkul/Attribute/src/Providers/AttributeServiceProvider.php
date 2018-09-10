<?php

namespace Webkul\Attribute\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class AttributeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
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