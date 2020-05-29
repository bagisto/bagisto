<?php

namespace Webkul\Checkout\Listeners;
use Cart;

class CustomerEventsHandler {

    /**
     * Handle Customer login events.
     */
    public function onCustomerLogin($event)
    {
        /**
         * handle the user login event to manage the after login, if the user has added any products as guest then
         * the cart items from session will be transferred from cookie to the cart table in the database.
         *
         * Check whether cookie is present or not and then check emptiness and then do the appropriate actions.
         */
        Cart::mergeCart();
    }

    /**
     * Handle cart changes
     */
    public function onCartChanged()
    {
        Cart::removeInactiveItems();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen('customer.after.login', 'Webkul\Checkout\Listeners\CustomerEventsHandler@onCustomerLogin');

        $events->listen('checkout.cart.add.before', 'Webkul\Checkout\Listeners\CustomerEventsHandler@onCartChanged');
        $events->listen('checkout.cart.item.add.before', 'Webkul\Checkout\Listeners\CustomerEventsHandler@onCartChanged');

        $events->listen('checkout.cart.update.before', 'Webkul\Checkout\Listeners\CustomerEventsHandler@onCartChanged');
        $events->listen('checkout.cart.item.update.before', 'Webkul\Checkout\Listeners\CustomerEventsHandler@onCartChanged');
    }
}