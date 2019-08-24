<?php

namespace Webkul\CustomerGroupCatalog\Providers;

use Illuminate\Support\ServiceProvider;

class CustomerGroupCatalogServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/admin-routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'customergroupcatalog');

        \Webkul\CustomerGroupCatalog\Models\CustomerGroup::observe(\Webkul\CustomerGroupCatalog\Observers\CustomerGroupObserver::class);

        $this->publishes([
            __DIR__ . '/../Resources/views/admin/customers/groups' => resource_path('views/vendor/admin/customers/groups'),
            __DIR__ . '/../Resources/views/shop/layouts' => resource_path('views/vendor/shop/layouts'),
        ]);
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