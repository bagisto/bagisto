<?php

namespace Webkul\SupplierInfo\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class SupplierInfoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'supplierinfo');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'supplierinfo');

        // Override the specific view
        View::composer('shop::products.view', function ($view) {
            $view->setPath(base_path('packages/Webkul/SupplierInfo/src/Resources/views/products/view.blade.php'));
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register package config.
     *
     * @return void
     */
}
