<?php

namespace Webkul\PreOrder\Observers;

use Webkul\PreOrder\Models\PreOrderItem;

use Company;

class PreOrderItemObserver
{
    public function creating(PreOrderItem $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}