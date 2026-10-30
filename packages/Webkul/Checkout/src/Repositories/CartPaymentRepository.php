<?php

namespace Webkul\Checkout\Repositories;

use Webkul\Checkout\Contracts\CartPayment;
use Webkul\Core\Eloquent\Repository;

class CartPaymentRepository extends Repository
{
    /**
     * Specify the model class name.
     */
    public function model(): string
    {
        return CartPayment::class;
    }
}
