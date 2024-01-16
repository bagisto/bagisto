<?php

namespace Webkul\Shop\Providers;

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
            'Webkul\Shop\Listeners\Customer@afterCreated',
        ],

        'customer.password.update.after' => [
            'Webkul\Shop\Listeners\Customer@afterPasswordUpdated',
        ],

        'customer.subscription.after' => [
            'Webkul\Shop\Listeners\Customer@afterSubscribed',
        ],

        'customer.note.create.after' => [
            'Webkul\Shop\Listeners\Customer@afterNoteCreated',
        ],

        'checkout.order.save.after' => [
            'Webkul\Shop\Listeners\Order@afterCreated',
        ],

        'sales.order.cancel.after' => [
            'Webkul\Shop\Listeners\Order@afterCanceled',
        ],

        'sales.order.comment.create.after' => [
            'Webkul\Shop\Listeners\Order@afterCommented',
        ],

        'sales.invoice.save.after' => [
            'Webkul\Shop\Listeners\Invoice@afterCreated',
        ],

        'sales.invoice.send_duplicate_email' => [
            'Webkul\Shop\Listeners\Invoice@afterCreated',
        ],

        'sales.shipment.save.after' => [
            'Webkul\Shop\Listeners\Shipment@afterCreated',
        ],

        'sales.refund.save.after' => [
            'Webkul\Shop\Listeners\Refund@afterCreated',
        ],
    ];
}
