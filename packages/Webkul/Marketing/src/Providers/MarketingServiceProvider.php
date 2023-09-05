<?php

namespace Webkul\Marketing\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Webkul\Marketing\Console\Commands\EmailsCommand;

class MarketingServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('campaign:process')->daily();
        });
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([EmailsCommand::class]);
        }
    }
}
