<?php

namespace Webkul\SAASCustomizer\Observers\Inventory;

use Webkul\SAASCustomizer\Models\Inventory\InventorySource;

use Company;

class InventorySourceObserver
{
    public function creating(InventorySource $model)
    {
        if(! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}