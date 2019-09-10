<?php

namespace Webkul\Product\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Product\Models\ProductProxy;
use Webkul\Product\Observers\ProductObserver;
use Webkul\Product\Console\Commands\PriceUpdate;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->app->register(EventServiceProvider::class);

        $this->publishes([
            dirname(__DIR__) . '/Config/imagecache.php' => config_path('imagecache.php'),
        ]);

        ProductProxy::observe(ProductObserver::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerCommands();
    }

    public function registerConfig() {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/product_types.php', 'product_types'
        );
    }

    /**
     * Register the console commands of this package
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole())
            $this->commands([PriceUpdate::class,]);
    }
}