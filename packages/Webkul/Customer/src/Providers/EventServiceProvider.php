<?php

namespace Webkul\Customer\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Webkul\Customer\Menu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->createCustomerAccountSideMenu();
    }

    /**
     * This method fires an event for menu creation, any package can add their menu item by listening to the customer.menu.build event
     *
     * @return void
     */

    public function createCustomerAccountSideMenu()
    {
        Event::listen('customer.menu.create', function () {
            return Menu::create(function ($menu) {
                Event::fire('customer.menu.build', $menu);
            });
        });

        Event::listen('customer.menu.build', function ($menu) {
            $menu->add('customer.account.profile', 'Profile');
            $menu->add('customer.account.profile', 'Wishlist');
        });
    }
}
