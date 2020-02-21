<?php

namespace Webkul\BookingProduct\Helpers;

use Carbon\Carbon;

/**
 * EventTicket Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class EventTicket extends Booking
{
    /**
     * Returns event date
     *
     * @param BookingProduct $bookingProduct
     * @return string
     */
    public function getEventDate($bookingProduct)
    {
        $from = Carbon::createFromTimeString($bookingProduct->available_from)->format('d F, Y h:i A');

        $to = Carbon::createFromTimeString($bookingProduct->available_to)->format('d F, Y h:i A');

        return $from . ' - ' . $to;
    }

    /**
     * Returns tickets
     *
     * @param BookingProduct $bookingProduct
     * @return array
     */
    public function getTickets($bookingProduct)
    {
        if (! $bookingProduct->event_tickets()->count()) {
            return;
        }

        return $this->formatPrice($bookingProduct->event_tickets);
    }

    /**
     * Format ticket price
     *
     * @param array $tickets
     * @return array
     */
    public function formatPrice($tickets)
    {
        foreach ($tickets as $index => $ticket) {
            $tickets[$index]['converted_price'] = core()->convertPrice($ticket->price);
            $tickets[$index]['formated_price'] = $formatedPrice = core()->currency($ticket->price);
            $tickets[$index]['formated_price_text'] = trans('bookingproduct::app.shop.products.per-ticket-price', ['price' => $formatedPrice]);
        }

        return $tickets;
    }
}