<?php

namespace Webkul\SAASCustomizer\Observers\Product;

use Webkul\SAASCustomizer\Models\Product\ProductImage;

use Company;

class ProductImageObserver
{
    public function creating(ProductImage $model)
    {
        // if (! auth()->guard('super-admin')->check()) {
        //     $model->company_id = Company::getCurrent()->id;
        // }
    }
}