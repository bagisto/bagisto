<?php

namespace Webkul\Product\Type;

/**
 * Class Bundle.
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Bundle extends AbstractType
{
    /**
     * Skip attribute for Bundle product type
     *
     * @var array
     */
    protected $skipAttributes = ['price', 'cost', 'special_price', 'special_price_from', 'special_price_to', 'width', 'height', 'depth', 'weight'];

    /**
     * These blade files will be included in product edit page
     * 
     * @var array
     */
    protected $additionalViews = [
        'admin::catalog.products.accordians.images',
        'admin::catalog.products.accordians.categories',
        'admin::catalog.products.accordians.bundle-items',
        'admin::catalog.products.accordians.product-links'
    ];
}