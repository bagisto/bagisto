<?php

namespace Webkul\Velocity\Providers;

use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

use Webkul\Velocity\Velocity;
use Webkul\Velocity\Facades\Velocity as VelocityFacade;

/**
 * Velocity ServiceProvider
 *
 * @author Vivek Sharma <viveksh047@webkul.com> @vivek-webkul
 * @author Shubham Mehrotra <shubhammehrotra.symfony@webkul.com> @shubhwebkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class VelocityServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/helpers.php';
        include __DIR__ . '/../Http/admin-routes.php';

        $this->app->register(EventServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'velocity');

        $this->publishes([
            __DIR__ . '/../../publishable/assets/' => public_path('themes/velocity/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../Resources/views/' => resource_path('themes/velocity/views'),
        ]);

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'velocity');

        $velocityHelper = app('Webkul\Velocity\Helpers\Helper');
        $velocityMetaData = $velocityHelper->getVelocityMetaData();

        view()->share('velocityMetaData', $velocityMetaData);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerFacades();

    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );
    }

    /**
     * Register Bouncer as a singleton.
     *
     * @return void
     */
    protected function registerFacades()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('velocity', VelocityFacade::class);

        $this->app->singleton('velocity', function () {
            return app()->make(Velocity::class);
        });
    }
}
