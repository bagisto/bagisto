<?php

namespace Webkul\Product\Type;

use Config;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;

/**
 * Class Configurable.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Configurable extends AbstractType
{
    /**
     * Skip attribute for downloadable product type
     *
     * @var array
     */
    protected $skipAttributes = ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'];

    /**
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.variations',
        'admin::catalog.products.accordians.product-links'
    ];

    /**
     * Return true if this product type is saleable
     *
     * @return array
     */
    public function isSaleable()
    {
        if (! $this->product->status)
            return false;
            
        return true;
    }

    /**
     * Return true if this product can have inventory
     *
     * @return array
     */
    public function isStockable()
    {
        return false;
    }
}