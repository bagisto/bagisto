<?php

namespace Webkul\SAASCustomizer\Observers\Discount;

use Webkul\SAASCustomizer\Models\Discount\CatalogRuleProducts;

use Company;

class CatalogRuleProductsObserver
{
    public function creating(CatalogRuleProducts $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}