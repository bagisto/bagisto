<?php

namespace Webkul\SAASCustomizer\Observers\Sales;

use Webkul\SAASCustomizer\Models\Sales\InvoiceItem;

use Company;

class InvoiceItemObserver
{
    public function creating(InvoiceItem $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}