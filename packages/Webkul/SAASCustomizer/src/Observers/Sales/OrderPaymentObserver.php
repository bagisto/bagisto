<?php

namespace Webkul\SAASCustomizer\Observers\Sales;

use Webkul\SAASCustomizer\Models\Sales\OrderPayment;

use Company;

class OrderPaymentObserver
{
    public function creating(OrderPayment $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}