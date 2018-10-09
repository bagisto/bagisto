<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Webkul\Ui\Menu;
use Webkul\Admin\ProductFormAccordian;

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

        $this->createProductFormAccordian();
    }

    /**
     * This method fires an event for menu creation, any package can add their menu item by listening to the admin.menu.build event
     *
     * @return void
     */
    public function createAdminMenu()
    {
        Event::listen('admin.menu.create', function () {
            return Menu::create(function ($menu) {
                Event::fire('admin.menu.build', $menu);
            });
        });

        Event::listen('admin.menu.build', function ($menu) {
            $menu->add('dashboard', 'Dashboard', 'admin.dashboard.index', 1, 'dashboard-icon');

            $menu->add('sales', 'Sales', 'admin.sales.orders.index', 1, 'sales-icon');

            $menu->add('catalog', 'Catalog', 'admin.catalog.products.index', 3, 'catalog-icon');

            $menu->add('catalog.products', 'Products', 'admin.catalog.products.index', 1);

            $menu->add('catalog.categories', 'Categories', 'admin.catalog.categories.index', 2);

            $menu->add('catalog.attributes', 'Attributes', 'admin.catalog.attributes.index', 3);

            $menu->add('catalog.families', 'Families', 'admin.catalog.families.index', 4);

            $menu->add('customers', 'Customers', 'admin.customer.index', 5, 'customer-icon');

            $menu->add('customers.customers', 'Customers', 'admin.customer.index', 1, '');

            $menu->add('customers.reviews', 'Review', 'admin.customer.review.index', 2, '');

            // $menu->add('customers.blocked_customer', 'Blocked Customers', 'admin.account.edit', 2, '');

            // $menu->add('customers.allowed_customer', 'Allowed Customers', 'admin.account.edit', 4, '');

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

            $menu->add('settings.sliders', 'Create Sliders', 'admin.sliders.index', 8, '');

            $menu->add('settings.tax', 'Taxes', 'admin.taxrule.index', 9, '');

            $menu->add('settings.tax.taxrule', 'Add Tax Rules', 'admin.taxrule.index', 1, '');

            $menu->add('settings.tax.taxrate', 'Add Tax Rates', 'admin.taxrate.index', 2, '');
        });
    }

    /**
     * Build route based ACL
     *
     * @return voidbuildACL
     */
    public function buildACL()
    {
        Event::listen('admin.acl.build', function ($acl) {
            $acl->add('dashboard', 'Dashboard', 'admin.dashboard.index', 1);

            $acl->add('catalog', 'Catalog', 'admin.catalog.index', 2);

            $acl->add('catalog.products', 'Products', 'admin.catalog.products.index', 1);

            $acl->add('catalog.categories', 'Categories', 'admin.catalog.categories.index', 1);

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
        $this->app->singleton('acl', function () {
            return current(Event::fire('admin.acl.create'));
        });

        View::share('acl', app('acl'));
    }

    /**
     * This method fires an event for accordian creation, any package can add their accordian item by listening to the admin.catalog.products.accordian.build event
     *
     * @return void
     */
    public function createProductFormAccordian()
    {
        Event::listen('admin.catalog.products.accordian.create', function() {
            return ProductFormAccordian::create(function($accordian) {
                Event::fire('admin.catalog.products.accordian.build', $accordian);
            });
        });

        Event::listen('admin.catalog.products.accordian.build', function($accordian) {
            $accordian->add('inventories', 'Inventories', 'admin::catalog.products.accordians.inventories', 1);

            $accordian->add('images', 'Images', 'admin::catalog.products.accordians.images', 2);

            $accordian->add('categories', 'Categories', 'admin::catalog.products.accordians.categories', 3);

            $accordian->add('variations', 'Variations', 'admin::catalog.products.accordians.variations', 4);
        });
    }
}
