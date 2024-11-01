<?php

namespace Frosko\DSK\Providers;

use Frosko\DSK\Services\DskBankConfig;
use Frosko\DSK\Services\DskBankService;
use Illuminate\Support\ServiceProvider;

class DSKServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../Http/routes.php');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->singleton(DskBankService::class, function () {
            $prefix = 'sales.payment_methods.dsk.';
            $config = new DskBankConfig(
                core()->getConfigData($prefix.'username'),
                core()->getConfigData($prefix.'password'),
                route('dsk.payment.success'),
                route('dsk.payment.failure'),
                route('dsk.payment.ipn'),
                core()->getConfigData($prefix.'test_mode') ?? false,
            );

            return new DskBankService($config);
        });

    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/paymentmethods.php', 'payment_methods'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php', 'core'
        );
    }
}
