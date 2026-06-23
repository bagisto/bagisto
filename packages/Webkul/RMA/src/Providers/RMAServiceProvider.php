<?php

namespace Webkul\RMA\Providers;

use Illuminate\Support\ServiceProvider;

class RMAServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
