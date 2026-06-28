<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Http\Middleware\NoCacheMiddleware;

class MarketplaceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'marketplace');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'marketplace');

        $this->registerRoutes();

        $this->app->register(EventServiceProvider::class);
    }

    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/marketplace.php', 'marketplace');

        $this->mergeConfigFrom(__DIR__.'/../Config/menu.php', 'menu.admin');

        $this->mergeConfigFrom(__DIR__.'/../Config/acl.php', 'acl');
    }

    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'admin', NoCacheMiddleware::class])
            ->prefix(config('app.admin_url').'/marketplace')
            ->name('admin.marketplace.')
            ->group(__DIR__.'/../Routes/admin-routes.php');

        Route::middleware(['web'])
            ->prefix('marketplace')
            ->name('marketplace.')
            ->group(__DIR__.'/../Routes/seller-routes.php');

        // Public seller storefront at /loja/{shop_url}
        Route::middleware(['web'])
            ->name('marketplace.')
            ->group(__DIR__.'/../Routes/shop-routes.php');
    }
}
