<?php

namespace Webkul\Marketplace\Providers;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings.
     *
     * @var array
     */
    protected $listen = [
        'checkout.order.save.after' => [
            [\Webkul\Marketplace\Listeners\OrderListener::class, 'afterOrderCreated'],
        ],

        'sales.order.cancel.after' => [
            [\Webkul\Marketplace\Listeners\OrderListener::class, 'afterOrderCanceled'],
        ],

        'sales.invoice.save.after' => [
            [\Webkul\Marketplace\Listeners\OrderListener::class, 'afterInvoiceCreated'],
        ],
    ];

    /**
     * Register the listeners.
     */
    public function boot(): void
    {
        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                \Illuminate\Support\Facades\Event::listen($event, $listener);
            }
        }
    }
}
