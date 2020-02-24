<?php

namespace Webkul\BookingProduct\Helpers;

/**
 * AppointmentSlot Helper
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AppointmentSlot extends Booking
{
    /**
     * @param integer        $qty
     * @param BookingProduct $bookingProduct
     * @return bool
     */
    public function haveSufficientQuantity($qty, $bookingProduct)
    {
        return true;
    }
}