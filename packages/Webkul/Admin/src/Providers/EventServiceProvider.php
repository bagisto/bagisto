<?php

namespace Webkul\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\Admin\Listeners\Admin;
use Webkul\Admin\Listeners\ChannelSettingsChange;
use Webkul\Admin\Listeners\Customer;
use Webkul\Admin\Listeners\Invoice;
use Webkul\Admin\Listeners\Order;
use Webkul\Admin\Listeners\Refund;
use Webkul\Admin\Listeners\Shipment;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        /**
         * Customer related events.
         */
        'customer.create.after' => [
            [Customer::class, 'afterCreated'],
        ],

        /**
         * Admin related events.
         */
        'admin.password.update.after' => [
            [Admin::class, 'afterPasswordUpdated'],
        ],

        /**
         * Sales related events.
         */
        'checkout.order.save.after' => [
            [Order::class, 'afterCreated'],
        ],

        'sales.order.cancel.after' => [
            [Order::class, 'afterCanceled'],
        ],

        'sales.invoice.save.after' => [
            [Invoice::class, 'afterCreated'],
        ],

        'sales.shipment.save.after' => [
            [Shipment::class, 'afterCreated'],
        ],

        'sales.refund.save.after' => [
            [Refund::class, 'afterCreated'],
        ],

        /**
         * Channel Settings related events.
         */
        'core.channel.update.after' => [
            [ChannelSettingsChange::class, 'checkForMaintenanceMode'],
        ],
    ];
}
