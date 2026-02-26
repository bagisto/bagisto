<?php

namespace Webkul\BookingProduct\Helpers;

class AppointmentSlot extends Booking
{
    /**
     * @param  \Webkul\BookingProduct\Contracts\BookingProduct  $bookingProduct
     */
    public function haveSufficientQuantity(int $qty, $bookingProduct): bool
    {
        return true;
    }
}
