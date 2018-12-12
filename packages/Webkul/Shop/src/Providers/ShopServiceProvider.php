<?php

namespace Webkul\Shop\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Webkul\Shop\Http\Middleware\Locale;
use Webkul\Shop\Http\Middleware\Theme;
use Webkul\Shop\Http\Middleware\Currency;
use Webkul\Shop\Providers\ComposerServiceProvider;
use Webkul\Ui\Menu;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Router $router)
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'shop');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'shop');

        $router->aliasMiddleware('locale', Locale::class);
        $router->aliasMiddleware('theme', Theme::class);
        $router->aliasMiddleware('currency', Currency::class);

        $this->app->register(ComposerServiceProvider::class);

        $this->composeView();

        $this->createCustomerMenu();
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $themes = $this->app->make('themes');

        if (!$themes->current() && \Config::get('themes.default')) {
            $themes->set(\Config::get('themes.default'));
        }
        
        $this->registerConfig();
    }

    /**
     * Bind the the data to the views
     *
     * @return void
     */
    protected function composeView()
    {
        view()->composer('shop::customers.account.partials.sidemenu', function ($view) {
            $menu = current(Event::fire('customer.menu.create'));

            $view->with('menu', $menu);
        });
    }

    /**
     * This method fires an event for menu creation, any package can add their menu item by listening to the customer.menu.build event
     *
     * @return void
     */
    public function createCustomerMenu()
    {
        Event::listen('customer.menu.create', function () {
            return Menu::create(function ($menu) {
                Event::fire('customer.menu.build', $menu);
            });
        });

        Event::listen('customer.menu.build', function ($menu) {
            foreach(config('menu.customer') as $item) {
                $menu->add($item['key'], $item['name'], $item['route'], $item['sort']);
            }
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
    }
}