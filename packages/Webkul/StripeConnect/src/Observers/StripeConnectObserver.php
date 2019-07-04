<?php

namespace Webkul\StripeConnect\Observers;

use Webkul\SAASCustomizer\Models\StripeConnect\StripeConnect;

use Company;

class StripeConnectObserver
{
    public function creating(StripeConnect $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}