<?php

namespace Webkul\SAASCustomizer\Observers\Sales;

use Webkul\SAASCustomizer\Models\Sales\OrderItemInventory;

use Company;

class OrderItemInventoryObserver
{
    public function creating(OrderItemInventory $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}