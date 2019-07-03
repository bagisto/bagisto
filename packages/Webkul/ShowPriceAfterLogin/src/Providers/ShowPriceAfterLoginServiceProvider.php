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
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'ShowPriceAfterLogin');

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/card.blade.php' => base_path('resources/views/vendor/shop/products/list/card.blade.php')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/price.blade.php' => base_path('resources/views/vendor/shop/products/price.blade.php')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/add-to-cart.blade.php' => base_path('resources/views/vendor/shop/products/add-to-cart.blade.php')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/buy-now.blade.php' => base_path('resources/views/vendor/shop/products/buy-now.blade.php')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/Shop/review-price.blade.php' => base_path('resources/views/vendor/shop/products/review-price.blade.php')
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
