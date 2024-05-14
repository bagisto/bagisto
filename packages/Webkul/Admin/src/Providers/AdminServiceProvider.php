<?php

namespace Webkul\Admin\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Collection;
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
            $view->with('menu', []);
            // $view->with('menu', $this->createMenu(config('menu.admin')));
        });

        view()->composer([
            'admin::settings.roles.create',
            'admin::settings.roles.edit',
        ], function ($view) {
            $view->with('acl', $this->createACL());
        });
    }

    /**
     * Mapping the menu items.
     */
    public function createMenu(array $items, $itemKey = null): Collection
    {
        return collect($items)
            ->sortBy('sort')
            ->filter(fn ($item) => bouncer()->hasPermission($itemKey ? $itemKey.'.'.$item['key'] : $item['key']))
            ->map(function ($item) {
                if (empty($item['items'])) {
                    return MenuItem::make(
                        key: $item['key'],
                        name: $item['name'],
                        route: $item['route'],
                        sort: $item['sort'],
                        icon: $item['icon'],
                    );
                }

                return MenuGroup::make(
                    key: $item['key'],
                    name: $item['name'],
                    route: $item['route'],
                    icon: $item['icon'],
                    sort: $item['sort'],
                    menuItems: $this->createMenu($item['items'], $item['key']),
                );
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
