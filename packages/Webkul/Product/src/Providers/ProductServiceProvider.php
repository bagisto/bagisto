<?php

namespace Webkul\Product\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Webkul\Product\Console\Commands\Indexer;
use Webkul\Product\Facades\ProductImage as ProductImageFacade;
use Webkul\Product\Facades\ProductVideo as ProductVideoFacade;
use Webkul\Product\Models\ProductProxy;
use Webkul\Product\Observers\ProductObserver;
use Webkul\Product\ProductImage;
use Webkul\Product\ProductVideo;

class ProductServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'product');

        $this->app->register(EventServiceProvider::class);

        ProductProxy::observe(ProductObserver::class);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->registerCommands();

        $this->registerFacades();
    }

    /**
     * Register configuration.
     */
    public function registerConfig(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__).'/Config/product_types.php', 'product_types');
    }

    /**
     * Register the console commands of this package.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([Indexer::class]);
        }
    }

    /**
     * Register Bouncer as a singleton.
     */
    protected function registerFacades(): void
    {
        /**
         * Product image.
         */
        $loader = AliasLoader::getInstance();

        $loader->alias('product_image', ProductImageFacade::class);

        $this->app->singleton('product_image', function () {
            return app()->make(ProductImage::class);
        });

        /**
         * Product video.
         */
        $loader->alias('product_video', ProductVideoFacade::class);

        $this->app->singleton('product_video', function () {
            return app()->make(ProductVideo::class);
        });
    }
}
