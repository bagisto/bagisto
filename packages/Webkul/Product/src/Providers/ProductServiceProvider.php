<?php

namespace Webkul\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Product\Providers\EventServiceProvider;
use Illuminate\Routing\Router;
use Webkul\Product\Models\Product;

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

        $this->app->register(EventServiceProvider::class);

        $this->composeView();

        $this->publishes([
            dirname(__DIR__) . '/Config/imagecache.php' => config_path('imagecache.php'),
        ]);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    public function registerConfig() {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/product_types.php', 'product_types'
        );
    }

    public function composeView() {
        view()->composer(['admin::catalog.products.create'], function ($view) {
            $items = array();

            foreach (config('product_types') as $item) {
                $item['children'] = [];

                array_push($items, $item);
            }

            $types = core()->sortItems($items);

            $view->with('productTypes', $types);
        });
    }
}