<?php

namespace Webkul\BookingProduct\Helpers;

class AppointmentSlot extends Booking
{
    /**
     * @param  int                                              $qty
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     * @return bool
     */
    public function haveSufficientQuantity(int $qty, $bookingProduct): bool
    {
        return true;
    }
}