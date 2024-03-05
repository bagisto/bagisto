<?php

namespace Webkul\CartRule\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class CartRuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->register(EventServiceProvider::class);
    }
}
