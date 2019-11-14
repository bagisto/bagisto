<?php

namespace Webkul\Discount\Helpers\Cart;

use Webkul\Discount\Repositories\CartRuleRepository as CartRule;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeOptionRepository;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Models\ProductAttributeValue;

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
    protected $attributeOptionRepository;

    /**
     * CartRuleRepository instance
     */
    protected $cartRule;

    /**
     * ProductAttributeValueRepository instance
     */
    protected $pav;

    /**
     * Condition symbols for matching the criteria with attributes selected
     */
    protected $symbols;

    public function __construct(
        Category $category,
        AttributeRepository $attributeRepository,
        Product $product,
        AttributeOptionRepository $attributeOptionRepository,
        CartRule $cartRule,
        ProductAttributeValue $pav
    )
    {
        $this->category = $category;

        $this->attribute = $attributeRepository;

        $this->product = $product;

        $this->attributeOptionRepository = $attributeOptionRepository;

        $this->cartRule = $cartRule;

        $this->pav = $pav;

        $this->conditionSymbols = config('pricerules.cart.conditions.symbols');
    }

    /**
     * Collects the attribute and category conditions
     *
     * @param Integer $ruleId
     *
     * @param Object $attrribute_conditions
     */
    public function convertX($ruleId, $attribute_conditions)
    {
        $attributeConditions = $attribute_conditions;

        $categoryValues = $attributeConditions->categories ?? null;

        $attributeValues = $attributeConditions->attributes ?? null;

        if (! isset($categoryValues) && ! isset($attributeValues))
            return false;

        $categoryResult = collect();

        if (isset($categoryValues) && count($categoryValues))
            $categoryResult = $this->convertFromCategories($categoryValues);

        $attributeResult = collect();

        if (isset($attributeValues) && count($attributeValues))
            $attributeResult = $this->convertFromAttributes($attributeValues);

        // now call the function that will find all the unique product ids
        $productIDs = $this->findAllUniqueIds($attributeResult, $categoryResult);

        // save the product ids against the cart rules
        return $this->saveIDs($ruleId, $productIDs) ? true : false;
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
            $selectedOptions = $attributeOption->value;

            if ($attributeOption->type == 'select' || $attributeOption->type == 'multiselect') {
                $attribute = $this->attribute->findWhere(['code' => $attributeOption->attribute]);

                $attributeOptions = $attribute->first()->options;

                $selectedAttributeOptions = collect();

                foreach ($attributeOptions as $attributeOption) {
                    foreach ($selectedOptions as $key => $value) {
                        if ($attributeOption->id == $value)
                            $selectedAttributeOptions->push($attributeOption);
                    }
                }

                foreach($selectedAttributeOptions as $selectedAttributeOption) {
                    $typeColumn = $this->pav::$attributeTypeFields[$attribute->first()->type];

                    $pavResults = $this->pav->where(
                        "{$typeColumn}", $selectedAttributeOption->id
                    )->get();

                    foreach ($pavResults as $pavResult) {
                        if ($pavResult->product->type == 'simple')
                            $products->push($pavResult->product);
                    }
                }
            } else {
                $attribute = $this->attribute->findWhere([
                    'code' => $attributeOption->attribute
                ]);

                $pavValues = $attribute->first();

                $selectedAttributeValues = collect();

                $foundProducts = collect();

                if ($attributeOption->attribute == 'sku') {
                    $testValue = $attributeOption->value;
                    $testCondition = $attributeOption->condition;

                    if ($testCondition == '{}') {
                        $foundProducts = $this->product->findWhere([
                            ['sku', 'like', '%'.$testValue.'%'],
                        ])->flatten()->all();
                    } else if ($testCondition == '!{}') {
                        $foundProducts = $this->product->findWhere([
                            ['sku', 'not like', '%'.$testValue.'%'],
                        ])->flatten()->all();
                    } else if ($testCondition == '=') {
                        $foundProducts = $this->product->findWhere([
                            ['sku', '=', $testValue],
                        ])->flatten()->all();
                    }
                }

                foreach($foundProducts as $foundProduct) {
                    $products->push($foundProduct);
                }
            }
        }

        return $products;
    }

    /**
     * This method will return product id from the attribute and attribute option params
     *
     * @param Collection $categories
     *
     * @return array
     */
    public function convertFromCategories($categories)
    {
        $products = collect();

        foreach ($categories as $category) {
            $data = $this->getAll($category->id);

            if ($data->count()) {
                $products->push($data);

                unset($data);
            }
        }

        return $products->flatten()->all();
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
        $attributeResult = $data[0] ?? collect();
        $categoryResult = $data[1] ?? collect();

        // find matched attribute options product ids
        $mergedCollection = $attributeResult->merge($categoryResult);

        $productIDs = collect();

        foreach ($mergedCollection as $merged) {
            $productIDs->push($merged->id);
        }

        // find all the unique product ids
        return $productIDs->unique()->flatten()->all();
    }

    /**
     * This method will save the product ids in the datastore
     *
     * @param integer $ruleId
     *
     * @param array $productIDs
     *
     * @return boolean
     */
    public function saveIDs($ruleId, $productIDs)
    {
        $cartRule = $this->cartRule->find($ruleId);

        $productIDs = implode(',', $productIDs);

        return $cartRule->update(['product_ids' => $productIDs]);
    }

    /**
     * To get all product ids lying in the criteria
     *
     * @param integer $categoryId
     *
     * @return Collection
     */
    public function getAll($categoryId = null)
    {
        return app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function($query) use($categoryId) {
            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                    ->addSelect('product_flat.*')
                    ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                    ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                    ->where('product_flat.channel', $channel)
                    ->where('product_flat.locale', $locale)
                    ->whereNotNull('product_flat.url_key');

            if ($categoryId)
                $qb->where('product_categories.category_id', $categoryId);

                $qb->where('product_flat.status', 1);

                $qb->where('product_flat.visible_individually', 1);

            $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function($qb) use($channel, $locale) {
                $qb->on('product_flat.id', '=', 'flat_variants.parent_id')
                    ->where('flat_variants.channel', $channel)
                    ->where('flat_variants.locale', $locale);
            });

            $qb = $qb->leftJoin('products as variants', 'products.id', '=', 'variants.parent_id');

            return $qb->groupBy('product_flat.id');
        })->get();
    }
}