<?php

namespace Webkul\Admin\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('checkout.order.save.after', 'Webkul\Admin\Listeners\Order@sendNewOrderMail');

        Event::listen('sales.invoice.save.after', 'Webkul\Admin\Listeners\Order@sendNewInvoiceMail');

        Event::listen('sales.shipment.save.after', 'Webkul\Admin\Listeners\Order@sendNewShipmentMail');

        Event::listen('checkout.order.save.after', 'Webkul\Admin\Listeners\Order@updateProductInventory');

        Event::listen('products.datagrid.sync', 'Webkul\Admin\Listeners\Product@sync');

        Event::listen('catalog.product.create.after', 'Webkul\Admin\Listeners\Product@afterProductCreated');

        Event::listen('catalog.product.update.after', 'Webkul\Admin\Listeners\Product@afterProductUpdate');

        // Event::listen('after.attribute.update', 'Webkul\Admin\Listeners\Product@updateColumnBasedOnAttribute');
    }
}