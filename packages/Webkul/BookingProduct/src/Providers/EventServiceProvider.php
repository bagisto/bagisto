<?php

namespace Webkul\BookingProduct\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Webkul\BookingProduct\Listeners\Order;
use Webkul\BookingProduct\Listeners\PriceNote;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'checkout.order.save.after' => [
            [Order::class, 'afterPlaceOrder'],
        ],

        'bagisto.shop.products.price.after' => [
            [PriceNote::class, 'addNote'],
        ],
    ];
}
