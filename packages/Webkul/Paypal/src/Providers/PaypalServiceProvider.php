<?php

namespace Webkul\Paypal\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class PaypalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'paypal');
    }
}
