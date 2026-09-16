<?php

namespace Webkul\Sales\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Sales\Contracts\OrderAddress;

class OrderAddressRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return OrderAddress::class;
    }
}
