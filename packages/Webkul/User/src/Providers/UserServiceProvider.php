<?php

namespace Webkul\User\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webkul\User\Http\Middleware\Bouncer as BouncerMiddleware;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        include __DIR__.'/../Http/helpers.php';
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('admin', BouncerMiddleware::class);

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
