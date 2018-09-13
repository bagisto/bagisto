<?php

namespace Webkul\Shipping\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;

class ShippingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {

        // $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

        // $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

        // include __DIR__ . '/../Http/routes.php';

        // $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'shipping');
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
