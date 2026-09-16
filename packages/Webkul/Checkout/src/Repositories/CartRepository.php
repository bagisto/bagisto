<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Checkout\Contracts\Cart;
use Webkul\Core\Eloquent\Repository;

class CartRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return Cart::class;
    }
}
