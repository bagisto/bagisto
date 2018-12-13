<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Webkul\Ui\Menu;
use Webkul\Core\Tree;
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

        $this->createAdminConfig();

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
            return Tree::create(function ($menu) {
                Event::fire('admin.menu.build', $menu);
            });
        });

        Event::listen('admin.menu.build', function ($menu) {
            foreach(config('menu.admin') as $item) {
                if (bouncer()->hasPermission($item['key'])) {
                    $menu->add($item, 'menu');
                }
            }
        });
    }

    /**
     * This method fires an event for config creation, any package can add their config item by listening to the admin.config.build event
     *
     * @return void
     */
    public function createAdminConfig()
    {
        Event::listen('admin.config.create', function () {
            return Tree::create(function ($tree) {
                Event::fire('admin.config.build', $tree);
            });
        });

        Event::listen('admin.config.build', function ($config) {
            foreach(config('core') as $item) {
                $config->add($item);
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
            foreach(config('acl') as $item) {
                $acl->add($item);
            }
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
