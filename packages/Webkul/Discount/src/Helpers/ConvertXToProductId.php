<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Models\ProductAttributeValue as ProductAttributeValue;
use DB;

class ConvertXToProductId
{
    /**
     * CategoryRepository instance
     */
    protected $category;

    /**
     * AttributeRepository instance
     */
    protected $attribute;

    /**
     * ProductRepository instance
     */
    protected $product;

     /**
      * AttributeOptionRepository instance
      */
    protected $attributeOption;

    /**
     * CartRuleRepository instance
     */
    protected $cartRule;

    /**
     * ProductAttributeValueRepository instance
     */
    protected $pav;

    public function __construct(
        Category $category,
        Attribute $attribute,
        Product $product,
        AttributeOption $attributeOption,
        CartRule $cartRule,
        ProductAttributeValue $pav
    )
    {
        $this->category = $category;

        $this->attribute = $attribute;

        $this->product = $product;

        $this->attributeOption = $attributeOption;

        $this->cartRule = $cartRule;

        $this->pav = $pav;
    }

    public function convertX($ruleId, $attribute_conditions)
    {
        $attributeConditions = json_decode($attribute_conditions);

        $categoryValues = $attributeConditions->categories;

        $attributeValues = $attributeConditions->attributes;

        if (!isset($categoryValues) && ! isset($attributeValues)) {
            return false;
        }

        $categoryResult = collect();

        if (isset($categoryValues) && count($categoryValues)) {
            $categoryResult = $this->convertFromCategories($categoryValues);
        }

        $attributeResult = collect();
        if (isset($attributeValues) && count($attributeValues)) {
            $attributeResult = $this->convertFromAttributes($attributeValues);
        }

        // now call the function that will find all the unique product ids
        $productIDs = $this->findAllUniqueIds($attributeResult, $categoryResult);

        // save the product ids against the cart rules
        $result = $this->saveIDs($ruleId, $productIDs);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This method will return product id from the attribute and attribute option params
     *
     * @return array
     */
    public function convertFromAttributes($attributeOptions)
    {
        $products = collect();

        foreach ($attributeOptions as $attributeOption) {
            if ($attributeOption->attribute == 'sku' || $attributeOption->attribute == 'type') {
                continue;
            }

            $selectedOptions = $attributeOption->value;

            if ($attributeOption->type == 'select' || $attributeOption->type == 'multiselect') {
                $pav = $this->attribute->findWhere([
                    'code' => $attributeOption->attribute
                ]);

                $pavOptions = $pav->first()->options;

                $selectedAttributeOptions = collect();

                foreach ($pavOptions as $pavOption) {
                    foreach ($selectedOptions as $key => $value) {
                        if ($pavOption->id == $value) {
                            $selectedAttributeOptions->push($pavOption);
                        }
                    }
                }

                foreach($selectedAttributeOptions as $selectedAttributeOption) {
                    $typeColumn = $this->pav::$attributeTypeFields[$pav->first()->type];

                    $pavResults = $this->pav->where(
                        "{$typeColumn}", $selectedAttributeOption->id
                    )->get();

                    foreach ($pavResults as $pavResult) {
                        if ($pavResult->product->type == 'simple')
                            $products->push($pavResult->product);
                    }
                }
            } else {
                $pav = $this->attribute->findWhere([
                    'code' => $attributeOption->attribute
                ]);

                $pavValues = $pav->first();

                dd($pavValues);

                // $selectedAttributeValues = collect();

                // foreach ($pavValues as $pavValue) {
                //     foreach ($selectedValues as $key => $value) {
                //         if ($pavValue->id == $value) {
                //             $selectedAttributeValues->push($pavValue);
                //         }
                //     }
                // }

                // foreach($selectedAttributeValues as $selectedAttributeValue) {
                //     $typeColumn = $this->pav::$attributeTypeFields[$pav->first()->type];

                //     $pavResults = $this->pav->where(
                //         "{$typeColumn}", $selectedAttributeValue->id
                //     )->get();

                //     foreach ($pavResults as $pavResult) {
                //         $products->push($pavResult->product);
                //     }
                // }
            }
        }

        return $products;
    }

    /**
     * This method will return product id from the attribute and attribute option params
     *
     * @return array
     */
    public function convertFromCategories($categories)
    {
        $products = collect();

        foreach ($categories as $category) {
            $data = $this->getAll($category->id);
        }

        return $data;
    }

    /**
     * This method will remove the duplicates from the array and merge all the items into single array
     *
     * @param array
     *
     * @return array
     */
    public function findAllUniqueIds(...$data)
    {
        $attributeResult = $data[0];
        $categoryResult = $data[1];

        // find matched attribute options product ids
        $mergedCollection = $attributeResult->merge($categoryResult);

        $productIDs = collect();
        foreach ($mergedCollection as $merged) {
            $productIDs->push($merged->id);
        }

        // find all the unique product ids
        $productIDs = $productIDs->unique();

        return $productIDs->flatten()->all();
    }

    /**
     * This method will save the product ids in the datastore
     *
     * @return boolean
     */
    public function saveIDs($ruleId, $productIDs)
    {
        $cartRule = $this->cartRule->find($ruleId);

        $productIDs = implode(',', $productIDs);

        return $cartRule->update([
            'product_ids' => $productIDs
        ]);
    }

    /**
     * @param integer $categoryId
     * @return Collection
     */
    public function getAll($categoryId = null)
    {
        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($categoryId) {
                    $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

                    $locale = request()->get('locale') ?: app()->getLocale();

                    $qb = $query->distinct()
                            ->addSelect('product_flat.id')
                            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                            ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                            ->where('product_flat.channel', $channel)
                            ->where('product_flat.locale', $locale)
                            ->whereNotNull('product_flat.url_key');

                    if ($categoryId) {
                        $qb->where('product_categories.category_id', $categoryId);
                    }

                    if (is_null(request()->input('status'))) {
                        $qb->where('product_flat.status', 1);
                    }

                    if (is_null(request()->input('visible_individually'))) {
                        $qb->where('product_flat.visible_individually', 1);
                    }

                    $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                        $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                            ->where('flat_variants.channel', $channel)
                            ->where('flat_variants.locale', $locale);
                    });

                    return $qb->groupBy('product_flat.id');
                })->get();

        return $results;
    }
}