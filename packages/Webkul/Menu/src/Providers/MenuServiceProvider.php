<?php

namespace Webkul\Menu\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Webkul\Menu\Facades\Menu as MenuFacade;
use Webkul\Menu\Menu as Menu;


class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        include __DIR__.'/../Http/helpers.php';
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('menu', MenuFacade::class);

        $this->app->singleton('menu', function () {
            return new Menu();
        });
    }
}
