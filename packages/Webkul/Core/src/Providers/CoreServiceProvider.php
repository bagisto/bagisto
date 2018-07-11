<?php

namespace Webkul\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Router;
use Illuminate\Foundation\AliasLoader;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'core');

        $this->publishes([
            __DIR__ . '/../../publishable/lang' => public_path('vendor/webkul/core/lang'),
        ], 'public');

        Validator::extend('slug', 'Webkul\Core\Contracts\Validations\Slug@passes');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }
}