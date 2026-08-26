<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Admin\CommandPalette\CommandPalette;
use Webkul\Admin\CommandPalette\Providers\ActionProvider;
use Webkul\Admin\CommandPalette\Providers\ConfigurationProvider;
use Webkul\Admin\CommandPalette\Providers\NavigationProvider;
use Webkul\Core\Http\Middleware\PreventRequestsDuringMaintenance;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->registerCommandPalette();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Route::middleware(['web', PreventRequestsDuringMaintenance::class])->group(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'admin');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'admin');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'admin');

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/command-palette.php',
            'command_palette'
        );
    }

    /**
     * Register what the command palette searches over.
     */
    protected function registerCommandPalette(): void
    {
        $this->app->singleton(CommandPalette::class);

        $this->callAfterResolving(CommandPalette::class, function (CommandPalette $palette) {
            $palette->register(NavigationProvider::class);
            $palette->register(ConfigurationProvider::class);
            $palette->register(ActionProvider::class);
        });
    }
}
