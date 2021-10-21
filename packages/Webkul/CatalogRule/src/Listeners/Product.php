<?php

namespace Webkul\CatalogRule\Listeners;

use Webkul\CatalogRule\Helpers\CatalogRuleIndex;

class Product
{
    /**
     * Product Repository Object
     * 
     * @var \Webkul\CatalogRule\Helpers\CatalogRuleIndex
     */
    protected $catalogRuleIndexHelper;

    /**
     * Create a new listener instance.
     * 
     * @param  \Webkul\CatalogRule\Helpers\CatalogRuleIndex  $catalogRuleIndexHelper
     * @return void
     */
    public function __construct(CatalogRuleIndex $catalogRuleIndexHelper)
    {
        $this->catalogRuleIndexHelper = $catalogRuleIndexHelper;
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