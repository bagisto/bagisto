<?php

namespace Webkul\Bulkupload\Providers;

use Illuminate\Support\ServiceProvider;

class BulkUploadServiceProvider extends ServiceProvider
{
    public function boot()
    {
        //
        include __DIR__ . '/../Http/admin-routes.php';

        $this->app->register(ModuleServiceProvider::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'bulkupload');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'bulkupload');

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
            dirname(__DIR__) . '/Config/admin-menu.php', 'menu.admin'
        );
    }
}
