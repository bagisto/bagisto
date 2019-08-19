<?php

namespace Webkul\Product\Helpers;

use Webkul\Product\Repositories\ProductGroupedProductRepository;

/**
 * Grouped Product Helper
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class GroupedProduct extends AbstractProduct
{
    /**
     * ProductGroupedProductRepository object
     *
     * @var object
     */
    protected $productGroupedProductRepository;

    /**
     * Create a new helper instance.
     *
     * @param  Webkul\Product\Repositories\ProductGroupedProductRepository $productGroupedProductRepository
     * @return void
     */
    public function __construct(ProductGroupedProductRepository $productGroupedProductRepository)
    {
        $this->productGroupedProductRepository = $productGroupedProductRepository;
    }
}