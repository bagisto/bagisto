<?php

namespace Webkul\SAASCustomizer\Observers\Customer;

use Webkul\SAASCustomizer\Models\Customer\CustomerAddress;

use Company;

class CustomerAddressObserver
{
    public function creating(CustomerAddress $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}