<?php

namespace Webkul\BookingProduct\Repositories;

use Webkul\BookingProduct\Contracts\BookingProductTableSlot;
use Webkul\Core\Eloquent\Repository;

class BookingProductTableSlotRepository extends Repository
{
    /**
     * Specify Model class name
     */
    public function model(): string
    {
        return BookingProductTableSlot::class;
    }
}
