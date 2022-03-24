<?php

namespace Webkul\BookingProduct\Repositories;

use Webkul\Core\Eloquent\Repository;

class BookingProductAppointmentSlotRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\BookingProduct\Contracts\BookingProductAppointmentSlot';
    }
}