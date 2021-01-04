<?php

namespace Webkul\Marketing\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
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

        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'marketing');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'marketing');
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