<?php

namespace Webkul\GDPR\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class GDPRServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->register(ModuleServiceProvider::class);
    }
}
