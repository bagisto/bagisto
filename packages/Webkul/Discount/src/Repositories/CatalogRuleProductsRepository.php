<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Illuminate\Container\Container as App;

/**
 * CatalogRuleProductsRepository
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleProductsRepository extends Repository
{
    /**
     * ProductRepository instance
     */
    protected $product;

    /**
     * CatalogRule Apply instance
     */
    protected $apply;

    /**
     * @param Product $product
     * @param App $app
     * @param Apply $apply
     */
    public function __construct(Product $product, App $app)
    {
        $this->product = $product;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return String
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CatalogRuleProducts';
    }
}
