<?php

namespace Webkul\Paytm\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Paytm\Console\Commands\InstallCommand;

class PaytmServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/paymentmethods.php',
            'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/frount-routes.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'paytm');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'paytm');

        $this->publishes([
            __DIR__.'/../Resources/assets/images' => storage_path('app/public/paytm'),
        ], 'public');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../Resources/lang' => resource_path('lang/vendor/paytm'),
            ], 'translations');

            $this->commands([
                InstallCommand::class,
            ]);
        }
    }
}
