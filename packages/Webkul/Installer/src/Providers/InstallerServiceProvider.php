<?php

namespace Webkul\Installer\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Installer\Console\Commands\Installer as InstallerCommand;
use Webkul\Installer\Http\Middleware\CanInstall;
use Webkul\Installer\Http\Middleware\Locale;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('install', [CanInstall::class]);

        $router->aliasMiddleware('installer_locale', Locale::class);

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'installer');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'installer');

        Event::listen('bagisto.installed', 'Webkul\Installer\Listeners\Installer@installed');
    }

    /**
     * Register the Installer Commands of this package.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallerCommand::class,
            ]);
        }
    }
}
