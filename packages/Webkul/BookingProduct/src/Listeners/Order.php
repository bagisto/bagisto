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
     * @var Object
     */
    protected $bookingRepository;

    /**
     * Create a new listener instance.
     *
     * @param  Webkul\Booking\Repositories\BookingRepository $bookingRepository
     * @return void
     */
    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * After sales order creation, add entry to bookings table
     *
     * @param mixed $order
     */
    public function afterPlaceOrder($order)
    {
        $this->bookingRepository->create(['order' => $order]);
    }
}