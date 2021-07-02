<?php

namespace Webkul\Customer\Providers;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webkul\Customer\Captcha;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customer');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'customer');

        $this->app['validator']->extend('captcha', function ($attribute, $value, $parameters) {
            return $this->app['captcha']->validateResponse($value);
        });
    }

    /**
     * Register services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->registerConfig();

        $this->registerEloquentFactoriesFrom(__DIR__ . '/../Database/Factories');

        $this->app->singleton('captcha', function ($app) {
            return new Captcha();
        });
    }

    /**
     * Register factories.
     *
     * @param  string  $path
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registerEloquentFactoriesFrom($path): void
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php',
            'core'
        );
    }
}
