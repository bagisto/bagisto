<?php

namespace Webkul\SAASCustomizer\Observers\Product;

use Webkul\SAASCustomizer\Models\Product\ProductAttributeValue;

use Company;

class ProductAttributeValueObserver
{
    public function creating(ProductAttributeValue $model)
    {
        if (! auth()->guard('super-admin')->check()) {
            $model->company_id = Company::getCurrent()->id;
        }
    }
}