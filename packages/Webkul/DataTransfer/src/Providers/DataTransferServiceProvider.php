<?php

namespace Webkul\DataTransfer\Providers;

use Illuminate\Support\ServiceProvider;

class DataTransferServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
}
