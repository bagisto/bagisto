<?php

namespace Webkul\SAASCustomizer\Observers\Discount;

use Webkul\SAASCustomizer\Models\Discount\CatalogRuleProductsPrice;

use Company;

class CatalogRuleProductsPriceObserver
{
    public function creating(CatalogRuleProductsPrice $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}