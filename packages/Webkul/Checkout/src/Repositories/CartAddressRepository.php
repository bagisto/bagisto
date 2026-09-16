<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Checkout\Contracts\CartAddress;
use Webkul\Core\Eloquent\Repository;

class CartAddressRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CartAddress::class;
    }
}
