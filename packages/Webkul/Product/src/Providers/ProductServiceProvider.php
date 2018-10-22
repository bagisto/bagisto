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

        // Event::listen('product.save.before', 'Webkul\Product\Listeners\ProductListener@beforeProductUpdate');

        Event::listen('product.save.after', 'Webkul\Product\Listeners\ProductListener@afterProductSave');

        // Event::listen('product.update.before', 'Webkul\Product\Listeners\ProductListener@beforeProductUpdate');

        Event::listen('product.update.after', 'Webkul\Product\Listeners\ProductListener@beforeProductUpdate');

        Event::listen('product.delete.after', 'Webkul\Product\Listeners\ProductListener@afterProductDelete');

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