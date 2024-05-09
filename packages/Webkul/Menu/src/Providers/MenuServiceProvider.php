<?php

namespace Webkul\Menu\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Webkul\Menu\Facades\Menu as MenuFacade;
use Webkul\Menu\Menu;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        include __DIR__.'/../Http/helpers.php';

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'menu');
        
        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'menu');

        $this->app->register(EventServiceProvider::class);
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
