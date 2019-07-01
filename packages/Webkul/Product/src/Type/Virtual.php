<?php

namespace Webkul\Product\Type;

use Config;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Shipping\Facades\Shipping;

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
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
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

    /**
     * Return true if item can be moved to cart from wishlist
     *
     * @return boolean
     */
    public function canBeMovedFromWishlistToCart()
    {
        return true;
    }
}