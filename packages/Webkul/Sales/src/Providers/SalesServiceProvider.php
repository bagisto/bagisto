<?php

namespace Webkul\Sales\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;

class SalesServiceProvider extends ServiceProvider
{

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