<?php

namespace Webkul\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Customer\Facades\Captcha;
use Webkul\Customer\Repositories\CustomerGroupRepository;

class CustomerServiceProvider extends ServiceProvider
{
    /**
     * Register application services. The group repository is shared for the request so the
     * guest group it memoizes is read once however many consumers ask for it.
     */
    public function register(): void
    {
        $this->app->scoped(CustomerGroupRepository::class);
    }

    /**
     * Bootstrap application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'customer');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'customer');

        $this->app['validator']->extend('captcha', function ($attribute, $value, $parameters) {
            return Captcha::getFacadeRoot()->validateResponse($value);
        });
    }
}
