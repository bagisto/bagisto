<?php

namespace Webkul\BookingProduct\Listeners;

use Webkul\BookingProduct\Repositories\BookingRepository;

class Order
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected BookingRepository $bookingRepository) {}

    /**
     * After sales order creation, add entry to bookings table
     *
     * @param  \Webkul\Sales\Contracts\Order  $order
     */
    public function afterPlaceOrder($order)
    {
        $this->bookingRepository->create(['order' => $order]);
    }
}
