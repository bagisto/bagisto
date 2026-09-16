<?php

namespace Webkul\Notification\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webkul\Notification\Listeners\Order;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('checkout.order.save.after', [Order::class, 'createOrder']);

        Event::listen('sales.order.update-status.after', [Order::class, 'updateOrder']);
    }
}
