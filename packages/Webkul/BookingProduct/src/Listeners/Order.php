<?php

namespace Webkul\BookingProduct\Listeners;

use Webkul\BookingProduct\Repositories\BookingRepository;

/**
 * Order Event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order
{
    /**
     * BookingRepository Object
     *
     * @var \Webkul\BookingProduct\Repositories\BookingRepository
     */
    protected $bookingRepository;

    /**
     * Create a new listener instance.
     *
     * @param  \Webkul\Booking\Repositories\BookingRepository  $bookingRepository
     * @return void
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * After sales order creation, add entry to bookings table
     *
     * @param \Webkul\Sales\Contracts\Order  $order
     */
    public function afterPlaceOrder($order)
    {
        $this->bookingRepository->create(['order' => $order]);
    }
}