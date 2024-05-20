<?php

namespace Webkul\Admin\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Tree;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     */
    public function boot(Router $router): void
    {
        Route::middleware('web')->group(__DIR__.'/../Routes/web.php');

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'admin');

        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'admin');

        Blade::anonymousComponentPath(__DIR__.'/../Resources/views/components', 'admin');

        $this->composeView();

        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/acl.php',
            'acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/system.php',
            'core'
        );
    }

    /**
     * Bind the data to the views.
     */
    protected function composeView(): void
    {
        view()->composer([
            'admin::components.layouts.header.index',
            'admin::components.layouts.sidebar.index',
            'admin::components.layouts.tabs',
        ], function ($view) {
            $tree = Tree::create();

            foreach (config('menu.admin') as $item) {
                if (! bouncer()->hasPermission($item['key'])) {
                    continue;
                }

                $tree->add($item, 'menu');
            }

            $tree->items = $tree->removeUnauthorizedUrls();

            $tree->items = core()->sortItems($tree->items);

            $view->with('menu', $tree);
        });
    }
}
