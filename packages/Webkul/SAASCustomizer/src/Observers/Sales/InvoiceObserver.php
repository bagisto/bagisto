<?php

namespace Webkul\SAASCustomizer\Observers\Sales;

use Webkul\SAASCustomizer\Models\Sales\Invoice;

use Company;

class InvoiceObserver
{
    public function creating(Invoice $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}