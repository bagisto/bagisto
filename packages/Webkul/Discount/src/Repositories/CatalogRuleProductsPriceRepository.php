<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;

/**
 * CatalogRuleProductsPriceRepository
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleProductsPriceRepository extends Repository
{
    /**
     * ProductRepository instance
     */
    protected $product;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Specify Model class name
     *
     * @return String
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CatalogRuleProductsPrice';
    }
}