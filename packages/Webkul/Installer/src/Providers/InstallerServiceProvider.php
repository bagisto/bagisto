<?php

namespace Webkul\Installer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Webkul\Installer\Http\Middleware\CanInstall;

class InstallerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @param \Illuminate\Routing\Router $router
     */
    public function boot(Router $router)
    {
        $router->middlewareGroup('install', [CanInstall::class]);
    }

    /**
     * Register the service provider
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();

        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'installer');

        Event::listen('bagisto.installed', 'Webkul\Installer\Listeners\Installer@installed');

        Event::listen('bagisto.updates.check', 'Webkul\Installer\Listeners\Installer@getUpdates');
    }

    /**
     * Register the Installer Commands of this package.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Webkul\Installer\Console\Commands\Install::class,
            ]);
        }
    }
}
