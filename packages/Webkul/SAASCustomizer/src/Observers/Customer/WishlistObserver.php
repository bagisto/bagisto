<?php

namespace Webkul\SAASCustomizer\Observers\Customer;

use Webkul\SAASCustomizer\Models\Customer\Wishlist;

use Company;

class WishlistObserver
{
    public function creating(Wishlist $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}