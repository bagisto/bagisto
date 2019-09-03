<?php

namespace Webkul\CustomerDocument\Observers;

use Webkul\CustomerDocument\Models\CustomerDocument;

use Company;

class CustomerDocumentObserver
{
    public function creating(CustomerDocument $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}