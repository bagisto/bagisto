<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Webkul\Admin\Providers\EventServiceProvider;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Webkul\Admin\Exceptions\Handler;
use Webkul\Core\Tree;

/**
 * Admin service provider
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AdminServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'admin');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('vendor/webkul/admin/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'admin');

        $this->composeView();

        $this->registerACL();

        $this->app->register(EventServiceProvider::class);

        $this->app->bind(
            ExceptionHandler::class,
            Handler::class
        );
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
     * Bind the the data to the views
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer(['admin::layouts.nav-left', 'admin::layouts.nav-aside', 'admin::layouts.tabs'], function ($view) {
            $tree = Tree::create();

            $replaceRoutes = array();

            $allowedPermissions = auth()->guard('admin')->user()->role->permissions;

            if ($allowedPermissions != 'all') {
                $withoutDots = array();

                foreach ($allowedPermissions as $key => $allowedPermission) {
                    if (!str_contains($allowedPermission, '.')) {
                        array_push($withoutDots, $allowedPermission);
                    }
                }

                $levelThreeKey = function() use($allowedPermissions, $withoutDots, $replaceRoutes) {
                    $collectSimilar = array();

                    foreach ($withoutDots as $key => $withoutDot) {
                        $group = array();

                        foreach ($allowedPermissions as $key1 => $allowedPermission) {
                            //pluck a level 3 group & match the dots
                            if (str_contains($allowedPermission, $withoutDot.'.')) {
                                array_push($group, $allowedPermission);
                            }
                        }

                        $collectSimilar[$key] = $group;
                        unset($group);
                    }

                    foreach ($collectSimilar as $collected)  {
                        if (count($collected) > 1) {
                            $first = $collected[0];
                            $second = $collected[1];

                            //level three detection
                            if (str_contains($second, $first.'.')) {
                                //find the missing key
                                foreach (config('menu.admin') as $key => $menuItem) {
                                    if ($menuItem['key'] == $first && $second != bouncer()->hasPermission(config('menu.admin')[$key+1]['key']) && !bouncer()->hasPermission(config('menu.admin')[$key+1]['key'])) {
                                        array_push($replaceRoutes, [$first, $second]);

                                        // config('menu.admin')[$key]['route']->set(['route' => 'cjj']);

                                        dd(config('menu.admin')[$key]['route']);

                                        break;
                                    }
                                }
                            }
                        }
                    }

                    return $replaceRoutes;
                };

                $x = $levelThreeKey();
                // dd($x);
            }

            foreach (config('menu.admin') as $item) {
                if (bouncer()->hasPermission($item['key'])) {
                    $tree->add($item, 'menu');
                }
            }

            $tree->items = core()->sortItems($tree->items);
            $view->with('menu', $tree);
        });

        view()->composer(['admin::users.roles.create', 'admin::users.roles.edit'], function ($view) {
            $view->with('acl', $this->createACL());
        });
    }

    /**
     * Registers acl to entire application
     *
     * @return void
     */
    public function registerACL()
    {
        $this->app->singleton('acl', function () {
            return $this->createACL();
        });
    }

    /**
     * Create acl tree
     *
     * @return mixed
     */
    public function createACL()
    {
        static $tree;

        if ($tree)
            return $tree;

        $tree = Tree::create();

        foreach (config('acl') as $item) {
            $tree->add($item, 'acl');
        }

        $tree->items = core()->sortItems($tree->items);

        return $tree;
    }

    /**
     * Register package config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/menu.php', 'menu.admin'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/acl.php', 'acl'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}