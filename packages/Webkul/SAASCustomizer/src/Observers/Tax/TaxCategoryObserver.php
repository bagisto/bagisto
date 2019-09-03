<?php

namespace Webkul\SAASCustomizer\Observers\Tax;

use Webkul\SAASCustomizer\Models\Tax\TaxCategory;

use Company;

class TaxCategoryObserver
{
    public function creating(TaxCategory $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}