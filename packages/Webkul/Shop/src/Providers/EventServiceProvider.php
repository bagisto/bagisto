<?php

namespace Webkul\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Webkul\Shop\Menu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->createStoreNavigationMenu();
    }

    /**
     * This method fires an event for menu creation, any package can add their menu item by listening to the customer.menu.build event
     *
     * @return void
     */

    public function createStoreNavigationMenu()
    {
        Event::listen('shop.navmenu.create', function () {
            return Menu::create(function ($menu) {
                Event::fire('shop.navmenu.build', $menu);
            });
        });

        Event::listen('shop.navmenu.build', function ($menu) {
            $menu->add('customer.account.profile', 'Profile');
            $menu->add('customer.account.profile', 'Wishlist');
        });
    }
}
