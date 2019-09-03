<?php

namespace Webkul\SAASCustomizer\Observers\Tax;

use Webkul\SAASCustomizer\Models\Tax\TaxRate;

use Company;

class TaxRateObserver
{
    public function creating(TaxRate $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}