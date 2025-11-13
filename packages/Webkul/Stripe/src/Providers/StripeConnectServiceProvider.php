<?php

namespace Webkul\Stripe\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class StripeConnectServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'stripe');

        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations', 'stripe');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'stripe');

        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');

        $this->app->register(EventServiceProvider::class);

        $this->app->register(ModuleServiceProvider::class);
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
     * Merge the stripe connect's configuration with the admin panel
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/paymentmethods.php', 'payment_methods'
        );
    }
}
