<?php

namespace Webkul\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;

class CustomerServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customer');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}
