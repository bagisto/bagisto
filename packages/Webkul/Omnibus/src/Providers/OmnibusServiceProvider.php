<?php

namespace Webkul\Omnibus\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Webkul\Omnibus\Console\Commands\PurgeOldSnapshots;
use Webkul\Omnibus\Console\Commands\SnapshotPrices;

class OmnibusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/omnibus.php', 'omnibus');

        $this->app->register(EventServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                SnapshotPrices::class,
                PurgeOldSnapshots::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'omnibus');

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('omnibus:snapshot-prices')->everyFifteenMinutes();

            $schedule->command('omnibus:purge-old-snapshots')->daily();
        });
    }
}
