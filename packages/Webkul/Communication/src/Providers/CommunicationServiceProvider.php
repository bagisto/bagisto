<?php

namespace Webkul\Communication\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class CommunicationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'communication');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'communication');

        $this->registerCommand();

        $this->setupScheduler();
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
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }

    /**
     * Register package commands.
     *
     * @return void
     */
    protected function registerCommand()
    {
        $this->commands([
            \Webkul\Communication\Console\Commands\SendNewsletter::class,
        ]);
    }

    /**
     * Setup your scheduler.
     *
     * @return void
     */
    protected function setupScheduler()
    {
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('newsletter:send')
                ->everyMinute()
                ->between(env('NEWSLETTER_START_TIME', '9:30'), env('NEWSLETTER_END_TIME', '19:00'));
        });
    }
}