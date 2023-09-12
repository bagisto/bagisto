<?php

namespace Webkul\FPC\Providers;

use Illuminate\Support\ServiceProvider;

class FPCServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(EventServiceProvider::class);
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
