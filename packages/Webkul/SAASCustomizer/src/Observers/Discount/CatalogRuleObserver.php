<?php

namespace Webkul\SAASCustomizer\Observers\Discount;

use Webkul\SAASCustomizer\Models\Discount\CatalogRule;

use Company;

class CatalogRuleObserver
{
    public function creating(CatalogRule $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}