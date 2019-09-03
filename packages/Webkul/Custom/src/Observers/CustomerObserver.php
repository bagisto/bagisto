<?php

namespace Webkul\Custom\Observers;

use Webkul\SAASCustomizer\Models\Customer\Customer;

use Company;

class CustomerObserver
{
    public function creating(Customer $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;

            $model->status = 0;
        }
    }
}