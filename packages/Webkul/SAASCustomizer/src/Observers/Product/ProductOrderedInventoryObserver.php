<?php

namespace Webkul\SAASCustomizer\Observers\Product;

use Webkul\SAASCustomizer\Models\Product\ProductOrderedInventory;

use Company;

class ProductOrderedInventoryObserver
{
    public function creating(ProductOrderedInventory $model)
    {
    }
}