<?php

namespace Webkul\BookingProduct\Repositories;

use Webkul\BookingProduct\Contracts\BookingProductAppointmentSlot;
use Webkul\Core\Eloquent\Repository;

class BookingProductAppointmentSlotRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return BookingProductAppointmentSlot::class;
    }
}
