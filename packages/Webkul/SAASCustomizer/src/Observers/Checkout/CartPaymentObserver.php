<?php

namespace Webkul\SAASCustomizer\Observers\Checkout;

use Webkul\SAASCustomizer\Models\Checkout\CartPayment;

use Company;

class CartPaymentObserver
{
    public function creating(CartPayment $model)
    {
    }
}