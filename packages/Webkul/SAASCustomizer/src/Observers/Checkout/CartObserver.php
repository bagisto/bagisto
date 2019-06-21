<?php

namespace Webkul\SAASCustomizer\Observers\Checkout;

use Webkul\SAASCustomizer\Models\Checkout\Cart;

use Company;

class CartObserver
{
    public function creating(Cart $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}