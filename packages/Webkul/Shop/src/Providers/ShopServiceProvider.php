<?php

namespace Webkul\Shop\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Blade;
use Webkul\Shop\Providers\ComposerServiceProvider;
use Webkul\Ui\Menu;

class ShopServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../Http/routes.php';

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'shop');

        $this->publishes([
            __DIR__ . '/../../publishable/assets' => public_path('themes/default/assets'),
        ], 'public');

        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'shop');

        $this->app->register(ComposerServiceProvider::class);

        $this->composeView();

        $this->createCustomerMenu();
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
            $menu->add('profile', 'Profile', 'customer.profile.index', 1);

            $menu->add('orders', 'Orders', 'customer.orders.index', 2);

            $menu->add('address', 'Address', 'customer.address.index', 3);

            $menu->add('reviews', 'Reviews', 'customer.reviews.index', 4);

            $menu->add('wishlist', 'Wishlist', 'customer.wishlist.index', 5);
        });
    }
}