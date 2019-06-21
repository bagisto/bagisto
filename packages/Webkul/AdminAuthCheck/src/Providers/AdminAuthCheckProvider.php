<?php

namespace Webkul\AdminAuthCheck\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\AdminAuthCheck\Providers\EventServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Webkul\SAASCustomizer\Exceptions\Handler;
use Illuminate\Routing\Router;

// use Webkul\Sales\Providers\ModuleServiceProvider;


class AdminAuthCheckServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );

        // $this->app->register(ModuleServiceProvider::class);
    }

    public function register()
    {
        $this->app->register(EventServiceProvider::class);
    }
}