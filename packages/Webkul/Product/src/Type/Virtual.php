<?php

namespace Webkul\Product\Type;

/**
 * Class Virtual.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Virtual extends AbstractType
{
    /**
     * Skip attribute for virtual product type
     *
     * @var array
     */
    protected $skipAttributes = ['width', 'height', 'depth', 'weight'];

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
     * Is a stokable product type
     *
     * @var boolean
     */
    protected $isStockable = false;

    /**
     * Show quantity box
     *
     * @var boolean
     */
    protected $showQuantityBox = true;

    /**
     * @param integer $qty
     * @return bool
     */
    public function haveSufficientQuantity($qty)
    {
        return $qty <= $this->totalQuantity() ? true : false;
    }
}