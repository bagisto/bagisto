<?php

namespace Webkul\Discount\Helpers;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductRepository as Product;

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

    public function __construct(
        Category $category,
        Attribute $attribute,
        Product $product,
        AttributeOption $attributeOption,
        CartRule $cartRule
    )
    {
        $this->category = $category;

        $this->attribute = $attribute;

        $this->product = $product;

        $this->attributeOption = $attributeOption;

        $this->cartRule = $cartRule;
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
    public function convertFromAttributes($attributes)
    {
        return $attributes;
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
        dd($data, 'product IDS');
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