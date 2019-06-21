<?php

namespace Webkul\SAASCustomizer\Observers\Checkout;

use Webkul\SAASCustomizer\Models\Checkout\CartItem;

use Company;

class CartItemObserver
{
    public function creating(CartItem $model)
    {
    }
}