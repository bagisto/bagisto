<?php

namespace Webkul\SAASCustomizer\Observers\Checkout;

use Webkul\SAASCustomizer\Models\Checkout\CartShippingRate;

use Company;

class CartShippingRateObserver
{
    public function creating(CartShippingRate $model)
    {
    }
}