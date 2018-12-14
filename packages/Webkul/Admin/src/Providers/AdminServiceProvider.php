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
        view()->composer(['admin::catalog.products.create', 'admin::catalog.products.edit'], function ($view) {
            $accordian = Tree::create();

            foreach(config('product_form_accordians') as $item) {
                $accordian->add($item);
            }

            $accordian->items = core()->sortItems($accordian->items);

            $view->with('form_accordians', $accordian);
        });

        view()->composer(['admin::layouts.nav-left', 'admin::layouts.nav-aside', 'admin::layouts.tabs'], function ($view) {
            $tree = Tree::create();

            foreach(config('menu.admin') as $item) {
                if (bouncer()->hasPermission($item['key'])) {
                    $tree->add($item, 'menu');
                }
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('menu', $tree);
        });

        view()->composer(['admin::layouts.nav-aside', 'admin::layouts.tabs', 'admin::configuration.index'], function ($view) {
            $tree = Tree::create();

            foreach(config('core') as $item) {
                $tree->add($item);
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('config', $tree);
        });

        view()->composer(['admin::users.roles.create', 'admin::users.roles.edit'], function ($view) {
            $tree = Tree::create();

            foreach(config('acl') as $item) {
                $tree->add($item);
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('acl', $tree);
        });
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
            dirname(__DIR__) . '/Config/product_form_accordians.php', 'product_form_accordians'
        );
    }
}