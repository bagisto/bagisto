<?php

namespace Webkul\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Webkul\User\Http\Middleware\RedirectIfNotAdmin;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;
use Webkul\Cart\Cart;
use Webkul\Cart\Facades\Cart as CartFacade;

class CartServiceProvider extends ServiceProvider
{

    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('cart', CartFacade::class);

        $this->app->singleton('cart', function () {
            return new Cart();
        });
    }
}
