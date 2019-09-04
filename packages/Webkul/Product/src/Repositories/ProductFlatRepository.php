<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Product Repository
 *
 * @author Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductFlatRepository extends Repository
{
    public function model()
    {
        return 'Webkul\Product\Contracts\ProductFlat';
    }

    /**
     * Maximum Price of Category Product
     *
     * @param Category $category
     * @return float
     */
    public function getCategoryProductMaximumPrice($category = null)
    {
        if (! $category)
            return $this->model->max('max_price');

        return $this->model
            ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
            ->where('product_categories.category_id', $category->id)
            ->max('max_price');
    }

    /**
     * get Category Product
     *
     * @return array
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