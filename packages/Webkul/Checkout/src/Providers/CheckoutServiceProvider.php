<?php

namespace Webkul\Checkout\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Webkul\Checkout\Facades\Cart;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class CheckoutServiceProvider extends ServiceProvider
{
    public function boot()
    {
        include __DIR__ . '/../Http/helpers.php';

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->app->register(ModuleServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this->registerEloquentFactoriesFrom(__DIR__ . '/../Database/Factories');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFacades();
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        //to make the cart facade and bind the
        //alias to the class needed to be called.
        $loader = AliasLoader::getInstance();

        $loader->alias('cart', Cart::class);

        $this->app->singleton('cart', function () {
            return new cart();
        });

        $this->app->bind('cart', 'Webkul\Checkout\Cart');
    }

    /**
     * Register factories.
     *
     * @param string $path
     *
     * @return void
     */
    protected function registerEloquentFactoriesFrom($path): void
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}