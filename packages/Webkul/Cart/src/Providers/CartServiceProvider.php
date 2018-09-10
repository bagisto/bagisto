<?php

namespace Webkul\Cart\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;
use Webkul\User\Http\Middleware\RedirectIfNotAdmin;

class CartServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $router->aliasMiddleware('admin', RedirectIfNotAdmin::class);

        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // $this->app->bind('datagrid', 'Webkul\Ui\DataGrid\DataGrid');
    }
}
