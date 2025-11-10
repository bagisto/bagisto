<?php

namespace Webkul\PayU\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PayUServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'payu');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'payu');

        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');

        $this->app->register(EventServiceProvider::class);
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

    /**
     * Merge the PayU configuration with payment methods
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/paymentmethods.php', 'payment_methods'
        );
    }
}