<?php

namespace Webkul\Inventory\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

class InventoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->app->make(EloquentFactory::class)->load(__DIR__ . '/../Database/Factories');
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