<?php

namespace Webkul\Admin\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'customer.registration.after' => [
            'Webkul\Admin\Listeners\Customer@afterCreated',
        ],

        'admin.password.update.after' => [
            'Webkul\Admin\Listeners\Admin@afterPasswordUpdated',
        ],

        'checkout.order.save.after' => [
            'Webkul\Admin\Listeners\Order@afterCreated',
        ],

        'sales.order.cancel.after' => [
            'Webkul\Admin\Listeners\Order@afterCanceled',
        ],

        'sales.invoice.save.after' => [
            'Webkul\Admin\Listeners\Invoice@afterCreated',
        ],

        'sales.shipment.save.after' => [
            'Webkul\Admin\Listeners\Shipment@afterCreated',
        ],

        'sales.refund.save.after' => [
            'Webkul\Admin\Listeners\Refund@afterCreated',
        ],

        'core.channel.update.after' => [
            'Webkul\Admin\Listeners\ChannelSettingsChange@checkForMaintenanceMode',
        ],
    ];
}
