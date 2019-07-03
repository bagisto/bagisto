<?php

namespace Webkul\SAASPreOrder\Observers;

use Webkul\SAASPreOrder\Models\PreOrderItem;

use Company;

class SAASPreOrderItemObserver
{
    public function creating(PreOrderItem $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}