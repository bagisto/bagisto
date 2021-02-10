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
        $qb = $this->categoryProductQuerybuilder($categoryId);

        $productFlatIds   = $qb->pluck('id')->toArray();
        $productIds       = $qb->pluck('product_flat.product_id')->toArray();

        $childProductIds = $this->model->distinct()
                            ->whereIn('parent_id', $productFlatIds)
                            ->pluck('product_id')->toArray();

        $productIds = array_merge($productIds, $childProductIds);

        $attributeValues = $this->model
                ->distinct()
                ->leftJoin('product_attribute_values as pa', 'product_flat.product_id', 'pa.product_id')
                ->leftJoin('attributes as at', 'pa.attribute_id', 'at.id')
                ->leftJoin('product_super_attributes as ps', 'product_flat.product_id', 'ps.product_id')
                ->select('pa.integer_value', 'pa.text_value', 'pa.attribute_id', 'ps.attribute_id as attributeId')
                ->where('is_filterable', 1)
                ->WhereIn('pa.product_id', $productIds)
                ->get();

        $attributeInfo['attributeOptions'] =  $attributeInfo['attributes'] = [];

        foreach ($attributeValues as $attribute) {
            $attributeKeys = array_keys($attribute->toArray());

            foreach ($attributeKeys as $key) {
                if (! is_null($attribute[$key])) {
                    if ($key == 'integer_value' && ! in_array($attribute[$key], $attributeInfo['attributeOptions'])) {
                        array_push($attributeInfo['attributeOptions'], $attribute[$key]);
                    } else if ($key == 'text_value' && ! in_array($attribute[$key], $attributeInfo['attributeOptions'])) {
                        $multiSelectArrributes = explode(",", $attribute[$key]);

                        foreach ($multiSelectArrributes as $multi) {
                            if (! in_array($multi, $attributeInfo['attributeOptions'])) {
                                array_push($attributeInfo['attributeOptions'], $multi);
                            }
                        }
                    } else if (($key == 'attribute_id' || $key == 'attributeId') && ! in_array($attribute[$key], $attributeInfo['attributes'])) {
                        array_push($attributeInfo['attributes'], $attribute[$key]);
                    }
                }
            }
        }

        return $attributeInfo;
    }

    /**
     * get Category Product Model
     *
     * @param  int  $categoryId
     * @return \Illuminate\Support\Querybuilder
    */
    public function categoryProductQuerybuilder($categoryId) {

        return $this->model
            ->leftJoin('product_categories', 'product_flat.product_id', 'product_categories.product_id')
            ->where('product_categories.category_id', $categoryId)
            ->where('product_flat.channel', core()->getCurrentChannelCode())
            ->where('product_flat.locale', app()->getLocale());
    }

    /**
     * filter attributes according to products
     *
     * @param  array  $category
     * @return \Illuminate\Support\Collection
     */
    public function getProductsRelatedFilterableAttributes($category)
    {
        $productsCount = $this->categoryProductQuerybuilder($category->id)->count();

        if ($productsCount > 0) {
            $categoryFilterableAttributes = $category->filterableAttributes->pluck('id')->toArray();

            $productCategoryArrributes = $this->getCategoryProductAttribute($category->id);

            $allFilterableAttributes = array_filter(array_unique(array_intersect($categoryFilterableAttributes, $productCategoryArrributes['attributes'])));

            $attributes = app('Webkul\Attribute\Repositories\AttributeRepository')->getModel()::with(['options' => function($query) use ($productCategoryArrributes) {
                    return $query->whereIn('id', $productCategoryArrributes['attributeOptions']);
                }
            ])->whereIn('id', $allFilterableAttributes)->get();

            return $attributes;
        } else {

            return $category->filterableAttributes;
        }
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