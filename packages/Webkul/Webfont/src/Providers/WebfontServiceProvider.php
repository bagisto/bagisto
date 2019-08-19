<?php

namespace Webkul\Webfont\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Webfont\Providers\EventServiceProvider;

class WebfontServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'webfont');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'webfont');

        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');

        $this->app->concord->registerModel(\Webkul\Webfont\Contracts\Webfont::class, \Webkul\Webfont\Models\Webfont::class);

        $this->app->register(EventServiceProvider::class);
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
     * Registers config
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );
    }
}
