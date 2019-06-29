<?php

namespace Webkul\CustomerDocument\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class CustomerDocumentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');

        $this->loadRoutesFrom(__DIR__ . '/../Http/front-routes.php');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customerdocument');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customerdocument');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');

        $this->publishes([
            dirname(__DIR__) . '/Resources/assets/sass/customerdocument.scss' => base_path('public/vendor/webkul/customerdocument/assets/css/customerdocument.css')
        ]);

        \Webkul\CustomerDocument\Models\CustomerDocument::observe(\Webkul\CustomerDocument\Observers\CustomerDocumentObserver::class);

        $this->app->register(ModuleServiceProvider::class);

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
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/customer-menu.php', 'menu.customer'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );
    }
}