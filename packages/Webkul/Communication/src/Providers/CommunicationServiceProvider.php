<?php

namespace Webkul\Communication\Providers;

use Illuminate\Support\ServiceProvider;

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

        $this->commands([
            \Webkul\Communication\Console\Commands\SendNewsletter::class,
        ]);
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
}