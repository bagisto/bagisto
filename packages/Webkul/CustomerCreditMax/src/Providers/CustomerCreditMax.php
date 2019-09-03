<?php

namespace Webkul\CustomerCreditMax\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class CustomerCreditMaxServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customercreditmax');

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}