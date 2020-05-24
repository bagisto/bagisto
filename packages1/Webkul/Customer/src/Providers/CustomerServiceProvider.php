<?php

namespace Webkul\Customer\Providers;

use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Webkul\Customer\Http\Middleware\RedirectIfNotCustomer;

class CustomerServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->aliasMiddleware('customer', RedirectIfNotCustomer::class);

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customer');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->registerEloquentFactoriesFrom(__DIR__ . '/../Database/Factories');
    }

    /**
     * Register factories.
     *
     * @param string $path
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function registerEloquentFactoriesFrom($path): void
    {
        $this->app->make(EloquentFactory::class)->load($path);
    }
}
