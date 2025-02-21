<?php

namespace Webkul\BookingProduct\Repositories;

use Webkul\BookingProduct\Contracts\BookingProductDefaultSlot;
use Webkul\Core\Eloquent\Repository;

class BookingProductDefaultSlotRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return BookingProductDefaultSlot::class;
    }
}
