<?php

namespace Webkul\SAASCustomizer\Observers\Sales;

use Webkul\SAASCustomizer\Models\Sales\ShipmentItem;

use Company;

class ShipmentItemObserver
{
    public function creating(ShipmentItem $model)
    {
        // if (! auth()->guard('super-admin')->check()) {
        //     $model->company_id = Company::getCurrent()->id;
        // }
    }
}