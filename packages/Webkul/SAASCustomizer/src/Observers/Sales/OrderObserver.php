<?php

namespace Webkul\SAASCustomizer\Observers\Sales;

use Webkul\SAASCustomizer\Models\Sales\Order;

use Company;

class OrderObserver
{
    public function creating(Order $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}