<?php

namespace Webkul\SAASCustomizer\Observers\Product;

use Webkul\SAASCustomizer\Models\Product\ProductFlat;

use Company;

class ProductFlatObserver
{
    public function creating(ProductFlat $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}