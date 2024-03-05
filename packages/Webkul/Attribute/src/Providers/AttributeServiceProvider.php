<?php

namespace Webkul\Attribute\Providers;

use Illuminate\Support\ServiceProvider;

class AttributeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
