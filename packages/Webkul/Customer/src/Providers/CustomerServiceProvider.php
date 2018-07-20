<?php

namespace Webkul\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Blade;
use Webkul\Admin\Providers\EventServiceProvider;

// use Webkul\Admin\Providers\ComposerServiceProvider;

class CustomerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/customer/assets'),
        ], 'public');

        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customer');
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
