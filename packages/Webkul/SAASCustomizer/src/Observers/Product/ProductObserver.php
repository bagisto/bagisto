<?php

namespace Webkul\SAASCustomizer\Observers\Product;

use Webkul\SAASCustomizer\Models\Product\Product;

use Company;

class ProductObserver
{
    public function creating(Product $model)
    {
        if(! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}