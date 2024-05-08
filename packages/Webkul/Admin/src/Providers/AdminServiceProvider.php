<?php

namespace Webkul\Admin\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Tree;
use Webkul\Menu\Facades\Menu;
use Webkul\Menu\Menu\MenuGroup;
use Webkul\Menu\Menu\MenuItem;

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

        $this->registerACL();

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
            $view->with('menu', $tree = $this->createMenuTree());

            $menus = collect($tree->items)->map(function ($item) {
                if (collect($item['children'])->isNotEmpty()) {
                    return MenuItem::make(
                        static fn () => trans($item['name']),
                        $item['route'],
                    )
                        ->icon($item['icon']);
                }

                $menuItems = collect($item['children'])
                    ->map(function ($child) {
                        return MenuItem::make(
                            static fn () => trans($child['name']),
                            $child['route'],
                            $child['key'],
                        )
                            ->icon($child['icon']);
                    })
                    ->values()
                    ->toArray();

                return MenuGroup::make(
                    static fn () => trans($item['name']),
                    $menuItems,
                    $item['icon'],
                )
                    ->icon($item['icon'])
                    ->setRoute($item['route']);
            });

            Menu::register($menus);
        });

        view()->composer([
            'admin::settings.roles.create',
            'admin::settings.roles.edit',
        ], function ($view) {
            $view->with('acl', $this->createACL());
        });
    }

    /**
     * Register ACL to entire application.
     */
    protected function registerACL(): void
    {
        $this->app->singleton('acl', function () {
            return $this->createACL();
        });
    }

    /**
     * Create menu tree.
     */
    public function createMenuTree(): Tree
    {
        static $tree;

        if ($tree) {
            return $tree;
        }

        $tree = Tree::create();

        foreach (config('menu.admin') as $item) {
            if (! bouncer()->hasPermission($item['key'])) {
                continue;
            }

            $tree->add($item, 'menu');
        }

        $tree->items = $tree->removeUnauthorizedUrls();

        $tree->items = core()->sortItems($tree->items);

        return $tree;
    }

    /**
     * Create ACL tree.
     */
    protected function createACL(): Tree
    {
        static $tree;

        if ($tree) {
            return $tree;
        }

        $tree = Tree::create();

        foreach (config('acl') as $item) {
            $tree->add($item, 'acl');
        }

        $tree->items = core()->sortItems($tree->items);

        return $tree;
    }
}
