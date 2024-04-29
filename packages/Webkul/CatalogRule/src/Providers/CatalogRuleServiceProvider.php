<?php

namespace Webkul\CatalogRule\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\CatalogRule\Console\Commands\PriceRuleIndex;

class CatalogRuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->app->register(EventServiceProvider::class);
    }

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
     * Register the console commands of this package.
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([PriceRuleIndex::class]);
        }
    }
}
