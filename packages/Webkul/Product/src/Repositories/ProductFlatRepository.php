<?php

namespace Webkul\Product\Repositories;

use Illuminate\Container\Container as App;
use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;

/**
 * Product Repository
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductFlatRepository extends Repository
{
    protected $product;

    /**
     * Price Object
     *
     * @var array
     */
    protected $price;

    public function __construct(
        Product $product,
        App $app
    ) {
        $this->product = $product;
        parent::__construct($app);
    }

    public function model()
    {
        return 'Webkul\Product\Contracts\ProductFlat';
    }

    /**
     * Maximum Price of Category Product
     *
     * @param int  $categoryId
     * return integer
     */
    public function getCategoryProductMaximumPrice($categoryId)
    {
        return $this->model
            ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
            ->where('product_categories.category_id', $categoryId)
            ->max('price');
    }

     /**
     * Maximum Price of Product
     *
     * return integer
     */
    public function getProductMaximumPrice()
    {
        return $this->model->max('price');
    }

    /**
     * get Category Product
     *
     * return array
     */
    public function getCategoryProduct($categoryId)
    {
        return $this->model
            ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
            ->where('product_categories.category_id', $categoryId)
            ->get();
    }
}