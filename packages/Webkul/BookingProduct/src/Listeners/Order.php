<?php

namespace Webkul\BookingProduct\Listeners;

use Webkul\BookingProduct\Repositories\BookingRepository;
use Webkul\BookingProduct\Models\BookingProductEventTicket;

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

        foreach($order->items as $item) {
            if($item->type !== 'booking') {
                continue;
            }

            $eventTicketId = $item->additional['booking']['ticket_id'];
            $eventTicket = BookingProductEventTicket::query()->findOrFail($eventTicketId);
            $eventTicket->update(['qty' => $eventTicket->qty - $item->qty_ordered]);
        }
    }
}