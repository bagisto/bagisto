<?php

namespace Webkul\User\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Webkul\User\Bouncer;
use Webkul\User\Facades\Bouncer as BouncerFacade;
use Webkul\User\Http\Middleware\Bouncer as BouncerMiddleware;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';

        $router->aliasMiddleware('admin', BouncerMiddleware::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerBouncer();
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerBouncer()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Bouncer', BouncerFacade::class);

        $this->app->singleton('bouncer', function () {
            return new Bouncer();
        });
    }
}
