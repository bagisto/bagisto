<?php

namespace Webkul\Product\Type;

/**
 * Class Simple.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Simple extends AbstractType
{
    /**
     * Skip attribute for simple product type
     *
     * @var array
     */
    protected $skipAttributes = [];

    /**
     * These blade files will be included in product edit page
     * 
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.inventories',
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.product-links'
    ];

    /**
     * Show quantity box
     *
     * @var boolean
     */
    protected $showQuantityBox = true;

    /**
     * Return true if this product type is saleable
     *
     * @return boolean
     */
    public function isSaleable()
    {
        if (! $this->product->status)
            return false;

        if ($this->haveSufficientQuantity(1))
            return true;

        return false;
    }

    /**
     * @param integer $qty
     *
     * @return boolean
     */
    public function haveSufficientQuantity($qty)
    {
        return $qty <= $this->totalQuantity() ? true : (core()->getConfigData('catalog.inventory.stock_options.backorders') ? true : false);
    }
}