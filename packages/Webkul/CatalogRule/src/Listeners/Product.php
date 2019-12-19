<?php

namespace Webkul\CatalogRule\Listeners;

use Webkul\CatalogRule\Helpers\CatalogRuleIndex;

/**
 * Products Event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Product
{
    /**
     * Product Repository Object
     * 
     * @var Object
     */
    protected $catalogRuleIndexHelper;

    /**
     * Create a new listener instance.
     * 
     * @param  Webkul\CatalogRule\Helpers\CatalogRuleIndex $catalogRuleIndexHelper
     * @return void
     */
    public function __construct(CatalogRuleIndex $catalogRuleIndexHelper)
    {
        $this->catalogRuleIndexHelper = $catalogRuleIndexHelper;
    }

    /**
     * @param Product $product
     * @return void
     */
    public function createProductRuleIndex($product)
    {
        $this->catalogRuleIndexHelper->reindexProduct($product);
    }
}