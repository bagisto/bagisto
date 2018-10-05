<?php

namespace Webkul\Checkout\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Webkul\User\Http\Middleware\RedirectIfNotAdmin;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Providers\ComposerServiceProvider;

class CheckoutServiceProvider extends ServiceProvider
{

    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $router->aliasMiddleware('admin', RedirectIfNotAdmin::class);

        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

        $this->app->register(ComposerServiceProvider::class);
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
        //to make the cart facade and bind the
        //alias to the class needed to be called.
        $loader = AliasLoader::getInstance();

        $loader->alias('cart', Cart::class);

        $this->app->singleton('cart', function () {
            return new cart();
        });

        $this->app->bind('cart', 'Webkul\Checkout\Cart');
    }
}