<?php

namespace Webkul\CatalogRule\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Webkul\CatalogRule\Console\Commands\PriceRuleIndex;

class CatalogRuleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('product:price-rule:index')->dailyAt('00:01');
        });

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register the console commands of this package.
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([PriceRuleIndex::class]);
        }
    }
}
