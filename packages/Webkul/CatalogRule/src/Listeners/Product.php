<?php

namespace Webkul\CatalogRule\Listeners;

use Webkul\CatalogRule\Helpers\CatalogRuleIndex;

class Product
{
    /**
     * Create a new listener instance.
     * 
     * @param  \Webkul\CatalogRule\Helpers\CatalogRuleIndex  $catalogRuleIndexHelper
     * @return void
     */
    public function __construct(protected CatalogRuleIndex $catalogRuleIndexHelper)
    {
    }

    /**
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function createProductRuleIndex($product)
    {
        $this->catalogRuleIndexHelper->reindexProduct($product);
    }
}