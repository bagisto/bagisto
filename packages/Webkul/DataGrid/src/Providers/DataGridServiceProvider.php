<?php

namespace Webkul\DataGrid\Providers;

use Illuminate\Support\ServiceProvider;

class DataGridServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        include __DIR__.'/../Http/helpers.php';
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
