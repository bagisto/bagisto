<?php

namespace Webkul\CustomerGroupCatalog\Observers;

use Webkul\CustomerGroupCatalog\Models\CustomerGroup;

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