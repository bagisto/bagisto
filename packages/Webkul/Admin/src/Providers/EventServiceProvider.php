<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Webkul\Ui\Menu;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->createAdminMenu();

        $this->buildACL();

        $this->registerACL();
    }

    /**
     * This method fires an event for menu creation, any package can add their menu item by listening to the admin.menu.build event
     *
     * @return void
     */
    public function createAdminMenu()
    {
        Event::listen('admin.menu.create', function() {
            return Menu::create(function($menu) {
                Event::fire('admin.menu.build', $menu);
            });
        });

        Event::listen('admin.menu.build', function($menu) {
            $menu->add('dashboard', 'Dashboard', 'admin.dashboard.index', 1, 'dashboard-icon');

            $menu->add('catalog', 'Catalog', 'admin.catalog.products.index', 3, 'catalog-icon');

            $menu->add('catalog.products', 'Products', 'admin.catalog.products.index', 1);

            $menu->add('catalog.categories', 'Categories', 'admin.catalog.categories.index', 2);

            $menu->add('catalog.attributes', 'Attributes', 'admin.catalog.attributes.index', 3);

            $menu->add('catalog.families', 'Families', 'admin.catalog.families.index', 4);

            $menu->add('configuration', 'Configure', 'admin.account.edit', 6, 'configuration-icon');

            $menu->add('configuration.account', 'My Account', 'admin.account.edit', 1);

            $menu->add('settings', 'Settings', 'admin.countries.index', 6, 'settings-icon');

            $menu->add('settings.countries', 'Countries', 'admin.countries.index', 1, '');

            $menu->add('settings.locales', 'Locales', 'admin.locales.index', 2, '');

            $menu->add('settings.currencies', 'Currencies', 'admin.currencies.index', 3, '');

            $menu->add('settings.exchange_rates', 'Exchange Rates', 'admin.exchange_rates.index', 4, '');

            $menu->add('settings.inventory_sources', 'Inventory Sources', 'admin.inventory_sources.index', 5, '');

            $menu->add('settings.channels', 'Channels', 'admin.channels.index', 5, '');

            $menu->add('settings.users', 'Users', 'admin.users.index', 7, '');

            $menu->add('settings.users.users', 'Users', 'admin.users.index', 1, '');

            $menu->add('settings.users.roles', 'Roles', 'admin.roles.index', 2, '');
        });
    }

    /**
     * Build route based ACL
     *
     * @return voidbuildACL
     */
    public function buildACL()
    {
        Event::listen('admin.acl.build', function($acl) {
            $acl->add('dashboard', 'Dashboard', 'admin.dashboard.index', 1);

            $acl->add('configuration', 'Configure', 'admin.account.edit', 5);

            $acl->add('settings', 'Settings', 'admin.users.index', 6);

            $acl->add('settings.users', 'Users', 'admin.users.index', 1);

            $acl->add('settings.users.users', 'Users', 'admin.users.index', 1);

            $acl->add('settings.users.roles', 'Roles', 'admin.roles.index', 2);
        });
    }

    /**
     * Registers acl to entire application
     *
     * @return void
     */
    public function registerACL()
    {
        $this->app->singleton('acl', function() {
            return current(Event::fire('admin.acl.create'));
        });

        View::share('acl', app('acl'));
    }
}
