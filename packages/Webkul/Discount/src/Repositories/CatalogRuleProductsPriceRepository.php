<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;

/**
 * Catalog Rule Products Price Reposotory
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleProductsPriceRepository extends Repository
{
    /**
     * ProductRepository instance
     */
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CatalogRuleProductsPrice';
    }

    /**
     * Create or update resource
     */
    public function createOrUpdate($rule, $productID)
    {
        if ($productID == '*') {

        } else {
            $products = $this->product->all('id');

            foreach ($products as $product) {

            }
        }
    }
}