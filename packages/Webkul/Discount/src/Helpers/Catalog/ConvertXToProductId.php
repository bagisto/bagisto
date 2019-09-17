<?php

namespace Webkul\Discount\Helpers\Catalog;

use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;
use Webkul\Attribute\Repositories\AttributeRepository as Attribute;
use Webkul\Attribute\Repositories\AttributeOptionRepository as AttributeOption;
use Webkul\Category\Repositories\CategoryRepository as Category;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Product\Models\ProductAttributeValue as ProductAttributeValue;

/**
 * ConvertXToProductID
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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
     * CatalogRuleRepository instance
     */
    protected $catalogRule;

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
        Attribute $attribute,
        Product $product,
        AttributeOption $attributeOption,
        CatalogRule $catalogRule,
        ProductAttributeValue $pav
    ) {
        $this->category = $category;

        $this->attribute = $attribute;

        $this->product = $product;

        $this->attributeOption = $attributeOption;

        $this->catalogRule = $catalogRule;

        $this->pav = $pav;

        $this->conditionSymbols = config('pricerules.cart.conditions.symbols');
    }

    public function convertX($attributeConditions)
    {
        $categoryValues = $attributeConditions->categories;

        $attributeValues = $attributeConditions->attributes;

        if (! isset($categoryValues) && ! isset($attributeValues)) {
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

        return $productIDs;
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
            if (isset($attributeOption->type) && $attributeOption->name != null && $attributeOption->condition != null && $attributeOption->value != [] && $attributeOption->type != null) {
                $selectedOptions = $attributeOption->value;

                if ($attributeOption->type == 'select' || $attributeOption->type == 'multiselect') {
                    $attribute = $this->attribute->findWhere([
                        'code' => $attributeOption->attribute
                    ]);

                    $attributeOptions = $attribute->first()->options;

                    $selectedAttributeOptions = collect();

                    foreach ($attributeOptions as $attributeOption) {
                        foreach ($selectedOptions as $key => $value) {
                            if ($attributeOption->id == $value) {
                                $selectedAttributeOptions->push($attributeOption);
                            }
                        }
                    }

                    foreach ($selectedAttributeOptions as $selectedAttributeOption) {
                        $typeColumn = $this->pav::$attributeTypeFields[$attribute->first()->type];

                        $pavResults = $this->pav->where(
                            "{$typeColumn}",
                            $selectedAttributeOption->id
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

                    if ($attributeOption->attribute == 'sku') {
                        $testValue = $attributeOption->value;
                        $testCondition = $attributeOption->condition;

                        if ($testCondition == '{}') {
                            $foundProducts = $this->product->findWhere([
                                ['sku', 'like', '%' . $testValue . '%'],
                                ['type', '!=', 'configurable']
                            ])->flatten()->all();
                        } else if ($testCondition == '!{}') {
                            $foundProducts = $this->product->findWhere([
                                ['sku', 'not like', '%' . $testValue . '%'],
                                ['type', '!=', 'configurable']
                            ])->flatten()->all();
                        } else if ($testCondition == '=') {
                            $foundProducts = $this->product->findWhere([
                                ['sku', '=', $testValue],
                                ['type', '!=', 'configurable']
                            ])->flatten()->all();
                        }
                    }

                    foreach ($foundProducts as $foundProduct) {
                        $products->push($foundProduct);
                    }
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
        $productIDs = $productIDs->unique();

        return $productIDs->flatten()->all();
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

        $results = app('Webkul\Product\Repositories\ProductFlatRepository')->scopeQuery(function ($query) use ($categoryId) {

            $channel = request()->get('channel') ?: (core()->getCurrentChannelCode() ?: core()->getDefaultChannelCode());

            $locale = request()->get('locale') ?: app()->getLocale();

            $qb = $query->distinct()
                ->select('products.id')
                ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
                ->leftJoin('product_categories', 'products.id', '=', 'product_categories.product_id')
                ->whereNotNull('product_flat.url_key');

            if ($categoryId) {
                $qb->where('product_categories.category_id', $categoryId);
            }

            $qb->where('product_flat.status', 1);

            $qb->where('product_flat.visible_individually', 1);

            $queryBuilder = $qb->leftJoin('product_flat as flat_variants', function ($qb) use ($channel, $locale) {
                $qb->on('product_flat.id', '=', 'flat_variants.parent_id');
            });

            return $qb->groupBy('product_flat.id');
        })->get();

        return $results;
    }
}
