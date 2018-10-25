<?php

namespace Webkul\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\Product\Models\Product;
use Webkul\Product\Observers\ProductObserver;
use Event;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        Event::listen('products.datagrid.create', 'Webkul\Admin\Listeners\Product@sync');

        Event::listen('product.save.after', 'Webkul\Admin\Listeners\Product@afterProductCreated');

        Event::listen('product.update.before',
        'Webkul\Admin\Listeners\Product@beforeProductUpdate');

        Event::listen('product.update.after',
        'Webkul\Admin\Listeners\Product@afterProductUpdate');

        Event::listen('product.delete.after', 'Webkul\Admin\Listeners\Product@afterProductDelete');

        Product::observe(ProductObserver::class);
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