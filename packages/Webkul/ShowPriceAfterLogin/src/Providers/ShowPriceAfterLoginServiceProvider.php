<?php

namespace Webkul\ShowPriceAfterLogin\Providers;

use Illuminate\Support\ServiceProvider;

class ShowPriceAfterLoginServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'ShowPriceAfterLogin');

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/price.blade.php' => base_path('packages/Webkul/Shop/src/Resources/views/products/price.blade.php')
        ]);
        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/add-to-cart.blade.php' => base_path('packages/Webkul/Shop/src/Resources/views/products/add-to-cart.blade.php')
        ]);
        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/add-buttons.blade.php' => base_path('packages/Webkul/Shop/src/Resources/views/products/add-buttons.blade.php')
        ]);
        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/buy-now.blade.php' => base_path('packages/Webkul/Shop/src/Resources/views/products/buy-now.blade.php')
        ]);
        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/view/product-add.blade.php' => base_path('packages/Webkul/Shop/src/Resources/views/products/view/product-add.blade.php')
        ]);
        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/review-price.blade.php' => base_path('packages/Webkul/Shop/src/Resources/views/products/review-price.blade.php')
        ]);

    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/../Config/system.php', 'core'
        );
    }
}
