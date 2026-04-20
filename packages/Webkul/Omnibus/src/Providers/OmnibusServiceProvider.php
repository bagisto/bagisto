<?php

namespace Webkul\Omnibus\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Webkul\Omnibus\Console\Commands\SnapshotPrices;

class OmnibusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'omnibus');
        
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'omnibus');

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('omnibus:snapshot-prices')->everyFifteenMinutes();
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SnapshotPrices::class,
            ]);
        }
    }
}
