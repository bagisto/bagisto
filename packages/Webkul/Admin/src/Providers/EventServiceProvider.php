<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Webkul\Ui\Menu;
use Webkul\Admin\ProductFormAccordian;

class EventServiceProvider extends ServiceProvider
{
    protected $menuItems = [
        [
            'key' => 'dashboard',
            'name' => 'Dashboard',
            'route' => 'admin.dashboard.index',
            'sort' => 1,
            'icon-class' => 'dashboard-icon',
        ], [
            'key' => 'sales',
            'name' => 'Sales',
            'route' => 'admin.sales.orders.index',
            'sort' => 2,
            'icon-class' => 'sales-icon',
        ], [
            'key' => 'sales.orders',
            'name' => 'Orders',
            'route' => 'admin.sales.orders.index',
            'sort' => 1,
            'icon-class' => '',
        ], [
            'key' => 'sales.shipments',
            'name' => 'Shipments',
            'route' => 'admin.sales.shipments.index',
            'sort' => 2,
            'icon-class' => '',
        ], [
            'key' => 'sales.invoices',
            'name' => 'Invoices',
            'route' => 'admin.sales.invoices.index',
            'sort' => 3,
            'icon-class' => '',
        ],
        [
            'key' => 'catalog',
            'name' => 'Catalog',
            'route' => 'admin.catalog.products.index',
            'sort' => 3,
            'icon-class' => 'catalog-icon',
        ], [
            'key' => 'catalog.products',
            'name' => 'Products',
            'route' => 'admin.catalog.products.index',
            'sort' => 1,
            'icon-class' => '',
        ], [
            'key' => 'catalog.categories',
            'name' => 'Categories',
            'route' => 'admin.catalog.categories.index',
            'sort' => 2,
            'icon-class' => '',
        ], [
            'key' => 'catalog.attributes',
            'name' => 'Attributes',
            'route' => 'admin.catalog.attributes.index',
            'sort' => 3,
            'icon-class' => '',
        ], [
            'key' => 'catalog.families',
            'name' => 'Families',
            'route' => 'admin.catalog.families.index',
            'sort' => 4,
            'icon-class' => '',
        ], [
            'key' => 'customers',
            'name' => 'Customers',
            'route' => 'admin.customer.index',
            'sort' => 4,
            'icon-class' => 'customer-icon',
        ], [
            'key' => 'customers.customers',
            'name' => 'Customers',
            'route' => 'admin.customer.index',
            'sort' => 1,
            'icon-class' => '',
        ], [
            'key' => 'customers.groups',
            'name' => 'Groups',
            'route' => 'admin.groups.index',
            'sort' => 2,
            'icon-class' => '',
        ], [
            'key' => 'customers.reviews',
            'name' => 'Reviews',
            'route' => 'admin.customer.review.index',
            'sort' => 3,
            'icon-class' => '',
        ], [
            'key' => 'configuration',
            'name' => 'Configure',
            'route' => 'admin.account.edit',
            'sort' => 5,
            'icon-class' => 'configuration-icon',
        ], [
            'key' => 'configuration.account',
            'name' => 'My Account',
            'route' => 'admin.account.edit',
            'sort' => 1,
            'icon-class' => '',
        ], 
        // [
        //     'key' => 'configuration.sales',
        //     'name' => 'Sales',
        //     'route' => 'admin.configuration.sales.general',
        //     'sort' => 1,
        //     'icon-class' => '',
        // ], [
        //     'key' => 'configuration.sales.general',
        //     'name' => 'General',
        //     'route' => 'admin.configuration.sales.general',
        //     'sort' => 1,
        //     'icon-class' => '',
        // ], 
        [
            'key' => 'settings',
            'name' => 'Settings',
            'route' => 'admin.locales.index',
            'sort' => 6,
            'icon-class' => 'settings-icon',
        ], [
            'key' => 'settings.locales',
            'name' => 'Locales',
            'route' => 'admin.locales.index',
            'sort' => 1,
            'icon-class' => '',
        ], [
            'key' => 'settings.currencies',
            'name' => 'Currencies',
            'route' => 'admin.currencies.index',
            'sort' => 2,
            'icon-class' => '',
        ], [
            'key' => 'settings.exchange_rates',
            'name' => 'Exchange Rates',
            'route' => 'admin.exchange_rates.index',
            'sort' => 3,
            'icon-class' => '',
        ], [
            'key' => 'settings.inventory_sources',
            'name' => 'Inventory Sources',
            'route' => 'admin.inventory_sources.index',
            'sort' => 4,
            'icon-class' => '',
        ], [
            'key' => 'settings.channels',
            'name' => 'Channels',
            'route' => 'admin.channels.index',
            'sort' => 5,
            'icon-class' => '',
        ], [
            'key' => 'settings.users',
            'name' => 'Users',
            'route' => 'admin.users.index',
            'sort' => 6,
            'icon-class' => '',
        ], [
            'key' => 'settings.users.users',
            'name' => 'Users',
            'route' => 'admin.users.index',
            'sort' => 1,
            'icon-class' => '',
        ], [
            'key' => 'settings.users.roles',
            'name' => 'Roles',
            'route' => 'admin.roles.index',
            'sort' => 2,
            'icon-class' => '',
        ], [
            'key' => 'settings.sliders',
            'name' => 'Sliders',
            'route' => 'admin.sliders.index',
            'sort' => 7,
            'icon-class' => '',
        ], [
            'key' => 'settings.taxes',
            'name' => 'Taxes',
            'route' => 'admin.tax-categories.index',
            'sort' => 8,
            'icon-class' => '',
        ], [
            'key' => 'settings.taxes.tax-categories',
            'name' => 'Tax Categories',
            'route' => 'admin.tax-categories.index',
            'sort' => 1,
            'icon-class' => '',
        ], [
            'key' => 'settings.taxes.tax-rates',
            'name' => 'Tax Rates',
            'route' => 'admin.tax-rates.index',
            'sort' => 2,
            'icon-class' => '',
        ],
    ];

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

        Event::listen('checkout.order.save.after', 'Webkul\Admin\Listeners\Order@sendNewOrderMail');

        Event::listen('checkout.invoice.save.after', 'Webkul\Admin\Listeners\Order@sendNewInvoiceMail');

        Event::listen('checkout.invoice.save.after', 'Webkul\Admin\Listeners\Order@sendNewShipmentMail');

        Event::listen('checkout.order.save.after', 'Webkul\Admin\Listeners\Order@updateProductInventory');
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
            foreach($this->menuItems as $item){
                if (bouncer()->hasPermission($item['key'])) {
                    $menu->add($item['key'], $item['name'], $item['route'], $item['sort'], $item['icon-class']);
                }
            }
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

            $acl->add('sales', 'Sales', 'admin.sales.orders.index', 2);

            $acl->add('sales.orders', 'Orders', 'admin.sales.orders.index', 1);

            $acl->add('sales.invoices', 'Invoices', 'admin.sales.invoices.index', 1);

            $acl->add('sales.shipments', 'Shipments', 'admin.sales.shipments.index', 1);

            $acl->add('catalog', 'Catalog', 'admin.catalog.index', 3);

            $acl->add('catalog.products', 'Products', 'admin.catalog.products.index', 1);

            $acl->add('catalog.categories', 'Categories', 'admin.catalog.categories.index', 2);

            $acl->add('catalog.attributes', 'Attributes', 'admin.catalog.attributes.index', 3);

            $acl->add('catalog.families', 'Families', 'admin.catalog.families.index', 4);
            
            $acl->add('customers', 'Customers', 'admin.customers.index', 4);

            $acl->add('customers.customers', 'Customers', 'admin.customers.index', 1);
            
            $acl->add('customers.groups', 'Groups', 'admin.groups.index', 2);

            $acl->add('customers.reviews', 'Reviews', 'admin.customers.reviews.index', 3);

            $acl->add('configuration', 'Configure', 'admin.account.edit', 5);

            $acl->add('settings', 'Settings', 'admin.users.index', 6);

            $acl->add('settings.locales', 'Locales', 'admin.locales.index', 1);

            $acl->add('settings.currencies', 'Currencies', 'admin.currencies.index', 2);

            $acl->add('settings.exchange_rates', 'Exchange Rates', 'admin.exchange_rates.index', 3);

            $acl->add('settings.inventory_sources', 'Inventory Sources', 'admin.inventory_sources.index', 4);

            $acl->add('settings.channels', 'Channels', 'admin.channels.index', 5);

            $acl->add('settings.users', 'Users', 'admin.users.index', 6);

            $acl->add('settings.users.users', 'Users', 'admin.users.index', 1);

            $acl->add('settings.users.roles', 'Roles', 'admin.roles.index', 2);

            $acl->add('settings.sliders', 'Sliders', 'admin.sliders.index', 7);

            $acl->add('settings.taxes', 'Taxes', 'admin.tax-categories.index', 7);

            $acl->add('settings.taxes.tax-categories', 'Tax Categories', 'admin.tax-categories.index', 1);

            $acl->add('settings.taxes.tax-rates', 'Tax Rates', 'admin.tax-rates.index', 2);
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
