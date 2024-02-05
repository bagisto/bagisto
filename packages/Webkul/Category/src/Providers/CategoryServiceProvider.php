<?php

namespace Webkul\Category\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Category\Models\CategoryProxy;
use Webkul\Category\Observers\CategoryObserver;

class CategoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        CategoryProxy::observe(CategoryObserver::class);
    }
}
