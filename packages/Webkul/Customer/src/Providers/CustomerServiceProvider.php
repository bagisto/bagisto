<?php

namespace Webkul\Customer\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webkul\Customer\Captcha;
use Illuminate\Database\Eloquent\Factories\Factory;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap application services.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router): void
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
     */
    public function register(): void
    {
        $this->registerConfig();

        $this->app->singleton('captcha', function ($app) {
            return new Captcha();
        });
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/Config/system.php', 'core');
    }
}
