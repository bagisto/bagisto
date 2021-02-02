<?php

namespace Webkul\Product\Repositories;

use Webkul\Core\Eloquent\Repository;

class ProductFlatRepository extends Repository
{
    public function model()
    {
        return 'Webkul\Product\Contracts\ProductFlat';
    }

    /**
     * Maximum Price of Category Product
     *
     * @param \Webkul\Category\Contracts\Category  $category
     * @return float
     */
    public function getCategoryProductMaximumPrice($category = null)
    {
        if (! $category) {
            return $this->model->max('max_price');
        }

        return $this->model
                    ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
                    ->where('product_categories.category_id', $category->id)
                    ->max('max_price');
    }

    /**
     * get Category Product Attribute
     *
     * @param  int  $categoryId
     * @return array
     */
    public function getCategoryProductAttribute($categoryId)
    {
        $qb = $this->model
                   ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
                   ->where('product_categories.category_id', $categoryId)
                   ->where('product_flat.channel', core()->getCurrentChannelCode())
                   ->where('product_flat.locale', app()->getLocale());

        $productArrributes = $qb->distinct()
                            ->leftJoin('product_attribute_values as pa', 'product_flat.product_id', 'pa.product_id')
                            ->leftJoin('attributes as at', 'pa.attribute_id', 'at.id')
                            ->where('is_filterable', 1);

        $productArrributesIds = $productArrributes->pluck('pa.attribute_id')->toArray();

        $productSelectArrributes = $productArrributes
                            ->pluck('integer_value')
                            ->toArray();

        $productmultiSelectArrributes = $productArrributes
                            ->pluck('text_value')
                            ->toArray();

        $multiSelectArrributes = [];
        foreach ($productmultiSelectArrributes as $multi) {
            if ($multi) {
                $multiSelectArrributes = explode(",", $multi);
            }
        }

        $productSuperArrributesIds = $qb->leftJoin('product_super_attributes as ps', 'product_flat.product_id', 'ps.product_id')
                                     ->pluck('ps.attribute_id')
                                     ->toArray();

        $productCategoryArrributes['attributeOptions'] = array_filter(array_unique(array_merge($productSelectArrributes, $multiSelectArrributes)));

        $productCategoryArrributes['attributes'] = array_filter(array_unique(array_merge($productArrributesIds, $productSuperArrributesIds)));

        return $productCategoryArrributes;
    }

    /**
     * filter attributes according to products
     *
     * @param  array  $category
     * @return \Illuminate\Support\Collection
     */
    public function getProductsRelatedFilterableAttributes($category)
    {
        $categoryFilterableAttributes = $category->filterableAttributes->pluck('id')->toArray();

        $productCategoryArrributes = $this->getCategoryProductAttribute($category->id);

        $allFilterableAttributes = array_filter(array_unique(array_merge($categoryFilterableAttributes, $productCategoryArrributes['attributes'])));

        $attributes = app('Webkul\Attribute\Repositories\AttributeRepository')->getModel()::with(['options' => function($query) use ($productCategoryArrributes) {
                return $query->whereIn('id', $productCategoryArrributes['attributeOptions']);
            }
        ])->whereIn('id', $allFilterableAttributes)->get();

        return $attributes;
    }

    /**
     * update product_flat custom column
     *
     * @param \Webkul\Attribute\Models\Attribute $attribute
     * @param \Webkul\Product\Listeners\ProductFlat $listener
     */
    public function updateAttributeColumn(
        \Webkul\Attribute\Models\Attribute $attribute ,
        \Webkul\Product\Listeners\ProductFlat $listener ) {
        return $this->model
            ->leftJoin('product_attribute_values as v', function($join) use ($attribute) {
                $join->on('product_flat.id', '=', 'v.product_id')
                    ->on('v.attribute_id', '=', \DB::raw($attribute->id));
            })->update(['product_flat.'.$attribute->code => \DB::raw($listener->attributeTypeFields[$attribute->type] .'_value')]);
    }
}