<?php

namespace Webkul\SAASCustomizer\Observers\Discount;

use Webkul\SAASCustomizer\Models\Discount\CartRule;

use Company;

class CartRuleObserver
{
    public function creating(CartRule $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}