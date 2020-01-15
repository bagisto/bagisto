<?php

namespace Webkul\Velocity\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
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
        include __DIR__ . '/../Http/front-routes.php';

        $this->app->register(EventServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'velocity');

        $this->publishes([
            __DIR__ . '/../../publishable/assets/' => public_path('themes/velocity/assets'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../Resources/views/' => resource_path('themes/velocity/views'),
        ]);

        Event::listen([
            'bagisto.admin.settings.locale.edit.after',
            'bagisto.admin.settings.locale.create.after',
        ], function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'velocity::admin.settings.locales.locale-logo'
                );
            }
        );

        Event::listen([
            'bagisto.admin.catalog.category.edit_form_accordian.general.after',
            'bagisto.admin.catalog.category.create_form_accordian.general.after',
        ], function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'velocity::admin.catelog.categories.category-icon'
                );
            }
        );

        Event::listen([
            'bagisto.admin.settings.slider.edit.after',
            'bagisto.admin.settings.slider.create.after',
        ], function($viewRenderEventManager) {
                $viewRenderEventManager->addTemplate(
                    'velocity::admin.settings.sliders.velocity-slider'
                );
            }
        );

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'velocity');

        $velocityHelper = app('Webkul\Velocity\Helpers\Helper');
        $velocityMetaData = $velocityHelper->getVelocityMetaData();

        view()->share('showRecentlyViewed', true);
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
