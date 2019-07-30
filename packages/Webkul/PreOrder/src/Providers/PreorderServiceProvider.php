<?php

namespace Webkul\PreOrder\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\PreOrder\Shipping;
use Illuminate\Foundation\AliasLoader;
use Webkul\Shipping\Facades\Shipping as ShippingFacade;

class PreOrderServiceProvider extends ServiceProvider
{
    protected $commands = [
        'Webkul\PreOrder\Commands\Console\GenerateData'
    ];

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->app->register(EventServiceProvider::class);

        $this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Http/front-routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'preorder');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'preorder');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/preorder/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../Resources/views/shop/products/add-buttons.blade.php' => resource_path('views/vendor/shop/products/add-buttons.blade.php'),
            __DIR__ . '/../Resources/views/shop/products/view/product-add.blade.php' => resource_path('views/vendor/shop/products/view/product-add.blade.php'),

            __DIR__ . '/../Resources/views/admin/sales/orders' => resource_path('views/vendor/admin/sales/orders'),
            __DIR__ . '/../Resources/views/shop/customers/account/orders' => resource_path('views/vendor/shop/customers/account/orders'),
        ]);

        //model observer for all the core models of Bagisto
        $this->bootModelObservers();
    }

    /**
     * Model observer for preorder model
     */
    public function bootModelObservers()
    {
        \Webkul\PreOrder\Models\PreOrderItem::observe(\Webkul\PreOrder\Observers\PreOrderItemObserver::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerFacades();

        $this->commands($this->commands);
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('shipping', ShippingFacade::class);

        $this->app->singleton('shipping', function () {
            return new Shipping();
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    public function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/purge-pool.php', 'purge-pool'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}