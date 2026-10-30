<?php

namespace Webkul\Checkout\Listeners;

use Illuminate\Events\Dispatcher;
use Webkul\Checkout\Facades\Cart;

class OrderEventsHandler
{
    /**
     * Take a completed buy now purchase's products back out of the shopper's basket, where the buy
     * now mirrored them; an order placed from an ordinary cart is left alone.
     */
    public function onOrderPlaced($order): void
    {
        Cart::removeBuyNowItemsFromBasket($order->cart_id);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen('checkout.order.save.after', [self::class, 'onOrderPlaced']);
    }
}
