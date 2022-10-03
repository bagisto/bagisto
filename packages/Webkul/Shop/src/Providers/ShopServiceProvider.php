<?php

namespace Webkul\Shop\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Webkul\Core\Tree;
use Webkul\Shop\Http\Middleware\Currency;
use Webkul\Shop\Http\Middleware\Locale;
use Webkul\Shop\Http\Middleware\Theme;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        /* publishers */
        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
            __DIR__ . '/../Resources/views'       => resource_path('themes/default/views'),
            __DIR__ . '/../Resources/lang'        => lang_path('vendor/shop'),
        ]);

        /* loaders */
        $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'shop');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'shop');

        /* aliases */
        $router->aliasMiddleware('locale', Locale::class);
        $router->aliasMiddleware('theme', Theme::class);
        $router->aliasMiddleware('currency', Currency::class);

        /* View Composers */
        $this->composeView();

        /* Paginator */
        Paginator::defaultView('shop::partials.pagination');
        Paginator::defaultSimpleView('shop::partials.pagination');

        /* Breadcrumbs */
        require __DIR__ . '/../Routes/breadcrumbs.php';
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
     * Bind the the data to the views.
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer('shop::customers.account.partials.sidemenu', function ($view) {
            $tree = Tree::create();

            foreach (config('menu.customer') as $item) {
                $tree->add($item, 'menu');
            }

            $tree->items = core()->sortItems($tree->items);

            $view->with('menu', $tree);
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
            dirname(__DIR__) . '/Config/menu.php', 'menu.customer'
        );

        $this->mergeConfigFrom(
            dirname(__DIR__) . '/Config/system.php', 'core'
        );
    }
}
