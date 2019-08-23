<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Discount\Repositories\CatalogRuleProductsRepository as CatalogRuleProduct;
use Illuminate\Container\Container as App;

/**
 * CatalogRuleProductsPriceRepository
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleProductsPriceRepository extends Repository
{
    /**
     * To hold ProductRepository instance
     */
    protected $product;

    /**
     * To hold CatalogRuleProductRepository instance
     *
     */
    protected $catalogRuleProduct;

    /**
     * @param Product $product
     */
    public function __construct(Product $product, CatalogRuleProduct $catalogRuleProduct,App $app)
    {

        $this->product = $product;

        $this->catalogRuleProduct = $catalogRuleProduct;

        parent::__construct($app);
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