<?php

namespace Webkul\SAASCustomizer\Observers\Customer;

use Webkul\SAASCustomizer\Models\Customer\Customer;

use Company;

class CustomerObserver
{
    public function creating(Customer $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}