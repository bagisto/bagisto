<?php

namespace Webkul\SAASCustomizer\Observers\Customer;

use Webkul\SAASCustomizer\Models\Customer\CustomerGroup;

use Company;

class CustomerGroupObserver
{
    public function creating(CustomerGroup $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}