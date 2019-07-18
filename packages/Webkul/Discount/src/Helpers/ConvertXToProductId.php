<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Models\ProductAttributeValue as ProductAttributeValue;

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

        if (count($categoryValues)) {
            $categoryResult = $this->convertFromCategories($categoryValues);
        }

        if (count($attributeValues)) {
            $attributeResult = $this->convertFromAttributes($attributeValues);
        }

        dd($attributeResult);

        // now call the function that will find all the unique product ids
        $productIds = $this->findAllUniqueIds($attributeResult, $categoryResult);

        // save the product ids against the cart rules
        $result = $this->saveIDs($ruleId, $productIds);

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
                        $products->push($pavResult->product);
                    }
                }
            } else {
                $pav = $this->attribute->findWhere([
                    'code' => $attributeOption->attribute
                ]);

                $pavValues = $pav->first();

                $selectedAttributeValues = collect();

                foreach ($pavValues as $pavValue) {
                    foreach ($selectedValues as $key => $value) {
                        if ($pavValue->id == $value) {
                            $selectedAttributeValues->push($pavValue);
                        }
                    }
                }

                foreach($selectedAttributeValues as $selectedAttributeValue) {
                    $typeColumn = $this->pav::$attributeTypeFields[$pav->first()->type];

                    $pavResults = $this->pav->where(
                        "{$typeColumn}", $selectedAttributeValue->id
                    )->get();

                    foreach ($pavResults as $pavResult) {
                        $products->push($pavResult->product);
                    }
                }
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
        return $categories;
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

        $pavs = $this->pav->all();

        // find matched attribute options product ids
        $attributeRelatedIds = $this->convertFromAttributes($attributeResult);

        dd($attributeRelatedIds);
        // find matched categories product ids
        $categoryRelatedIds = $this->convertFromCategories($categoryResult);
    }

    /**
     * This method will save the product ids in the datastore
     *
     * @return boolean
     */
    public function saveIDs($ruleId, $productIds)
    {
        $cartRule = $this->cartRule->find($ruleId);

        return $cartRule->update([
            'product_ids' => $productIds
        ]);
    }
}