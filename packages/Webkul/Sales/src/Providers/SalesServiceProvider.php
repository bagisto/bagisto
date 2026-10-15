<?php

namespace Webkul\Sales\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Webkul\Sales\Console\Commands\InvoiceOverdueCron;

class SalesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->registerCommands();

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('invoice:cron')->dailyAt('3:00');
        });
    }

    /**
     * Register the package's console commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InvoiceOverdueCron::class,
            ]);
        }
    }
}
