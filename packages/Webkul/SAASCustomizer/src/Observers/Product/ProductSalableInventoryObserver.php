<?php

namespace Webkul\SAASCustomizer\Observers\Product;

use Webkul\SAASCustomizer\Models\Product\ProductSalableInventory;

use Company;

class ProductSalableInventoryObserver
{
    public function creating(ProductSalableInventory $model)
    {
    }
}