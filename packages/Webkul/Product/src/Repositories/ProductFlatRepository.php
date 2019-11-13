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
        // return $this->model
        //     ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
        //     ->where('product_categories.category_id', $categoryId)
        //     ->max('price');

        return $this->model->max('product_flat.price');
    }

     /**
     * Maximum Price of Product
     *
     * return integer
     */
    public function getProductMaximumPrice()
    {
        return $this->model->max('product_flat.price');
    }

    /**
     * get Category Product
     *
     * return array
     */
    public function getCategoryProductAttribute($categoryId)
    {
        $qb = $this->model
            ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
            ->where('product_categories.category_id', $categoryId)
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->where('product_flat.locale', app()->getLocale());

        $productArrributes = $qb
            ->leftJoin('product_attribute_values as pa', 'product_flat.product_id', 'pa.product_id')
            ->pluck('pa.attribute_id')
            ->toArray();

        $productSuperArrributes = $qb
            ->leftJoin('product_super_attributes as ps', 'product_flat.product_id', 'ps.product_id')
            ->pluck('ps.attribute_id')
            ->toArray();

        $productCategoryArrributes = array_unique(array_merge($productArrributes, $productSuperArrributes));

        return $productCategoryArrributes;
    }
}