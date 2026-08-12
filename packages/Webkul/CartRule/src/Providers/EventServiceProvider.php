<?php

namespace Webkul\CartRule\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\CartRule\Listeners\Cart;
use Webkul\CartRule\Listeners\Order;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'checkout.order.save.after' => [
            [Order::class, 'manageCartRule'],
        ],

        'checkout.cart.collect.totals.before' => [
            [Cart::class, 'applyCartRules'],
        ],
    ];
}
