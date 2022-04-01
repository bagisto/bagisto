<?php

namespace Webkul\Sitemap\Providers;

use Illuminate\Support\ServiceProvider;

class SitemapServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->register(ModuleServiceProvider::class);
        
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }
}