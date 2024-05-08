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
     *
     * @return void
     */
    public function boot(Router $router)
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
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
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
     *
     * @return void
     */
    protected function composeView()
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

            $menus = collect($tree->items)->map(function ($item) {
                if (empty($item['children'])) {
                    return MenuItem::make(
                        trans($item['name']),
                        $item['route'],
                    )
                        ->icon($item['icon']);
                }

                $menuItems = collect($item['children'])
                    ->map(function ($child) {
                        return MenuItem::make(
                            trans($child['name']),
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
     *
     * @return void
     */
    protected function registerACL()
    {
        $this->app->singleton('acl', function () {
            return $this->createACL();
        });
    }

    /**
     * Create ACL tree.
     *
     * @return mixed
     */
    protected function createACL()
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
