<?php

namespace Webkul\Product\Exceptions;

use Exception;
use Throwable;

class InsufficientProductInventoryException extends Exception
{
    public function __construct(?string $message = null, ?Throwable $previous = null)
    {
        parent::__construct(
            $message ?? trans('product::app.checkout.cart.inventory-warning'),
            0,
            $previous
        );
    }
}
