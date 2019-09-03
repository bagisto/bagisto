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
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'showpriceafterlogin');

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/card.blade.php' => base_path('resources/views/vendor/shop/products/list/card.blade.php')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/price.blade.php' => base_path('resources/views/vendor/shop/products/price.blade.php')
        ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/add-to-cart.blade.php' => base_path('resources/views/vendor/shop/products/add-to-cart.blade.php')
        ]);

        // $this->publishes([
        //     dirname(__DIR__) . '/Resources/views/Shop/add-buttons.blade.php' => base_path('views/vendor/shop/products/add-buttons.blade.php')
        // ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/buy-now.blade.php' => base_path('resources/views/vendor/shop/products/buy-now.blade.php')
        ]);

        // $this->publishes([
        //     dirname(__DIR__) . '/Resources/views/Shop/view/product-add.blade.php' => base_path('views/vendor/shop/products/view/product-add.blade.php')
        // ]);

        $this->publishes([
            dirname(__DIR__) . '/Resources/views/review-price.blade.php' => base_path('resources/views/vendor/shop/products/review-price.blade.php')
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