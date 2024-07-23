<?php

namespace Webkul\CatalogRule\Helpers;

use Carbon\Carbon;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\CatalogRule\Repositories\CatalogRuleProductRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Rule\Helpers\Validator;

class CatalogRuleProduct
{
    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected ProductRepository $productRepository,
        protected CatalogRuleProductRepository $catalogRuleProductRepository,
        protected Validator $validator
    ) {}

    /**
     * Collect discount on cart
     *
     * @param  \Webkul\CatalogRule\Contracts\CatalogRule  $rule
     * @param  int  $batchCount
     * @return void
     */
    public function insertRuleProduct($rule, $batchCount = 1000, $product = null)
    {
        if (! (float) $rule->discount_amount) {
            return;
        }

        $productIds = $this->getMatchingProductIds($rule, $product);

        $rows = [];

        $startsFrom = $rule->starts_from ? Carbon::createFromTimeString($rule->starts_from.' 00:00:01') : null;

        $endsTill = $rule->ends_till ? Carbon::createFromTimeString($rule->ends_till.' 23:59:59') : null;

        $channelIds = $rule->channels->pluck('id');

        $customerGroupIds = $rule->customer_groups->pluck('id');

        foreach ($productIds as $productId) {
            foreach ($channelIds as $channelId) {
                foreach ($customerGroupIds as $customerGroupId) {
                    $rows[] = [
                        'starts_from'       => $startsFrom,
                        'ends_till'         => $endsTill,
                        'catalog_rule_id'   => $rule->id,
                        'channel_id'        => $channelId,
                        'customer_group_id' => $customerGroupId,
                        'product_id'        => $productId,
                        'discount_amount'   => $rule->discount_amount,
                        'action_type'       => $rule->action_type,
                        'end_other_rules'   => $rule->end_other_rules,
                        'sort_order'        => $rule->sort_order,
                    ];

                    if (count($rows) == $batchCount) {
                        $this->catalogRuleProductRepository->insert($rows);

                        $rows = [];
                    }
                }
            }
        }

        if (! empty($rows)) {
            $this->catalogRuleProductRepository->insert($rows);
        }
    }

    /**
     * Clean catalog rule product indices
     *
     * @param  \Webkul\CatalogRule\Contracts\CatalogRule  $rule
     * @return void
     */
    public function cleanRuleIndices($rule)
    {
        $this->catalogRuleProductRepository->where('catalog_rule_id', $rule->id)->delete();
    }

    /**
     * Clean products indices
     *
     * @param  array  $productIds
     * @return void
     */
    public function cleanProductIndices($productIds = [])
    {
        if (count($productIds)) {
            $this->catalogRuleProductRepository->whereIn('product_id', $productIds)->delete();
        } else {
            $this->catalogRuleProductRepository->deleteWhere([
                ['product_id', 'like', '%%'],
            ]);
        }
    }

    /**
     * Get array of product ids which are matched by rule
     *
     * @param  \Webkul\CatalogRule\Contracts\CatalogRule  $rule
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array
     */
    public function getMatchingProductIds($rule, $product = null)
    {
        $products = $this->productRepository->scopeQuery(function ($query) use ($rule, $product) {
            $query = $query->addSelect('products.*');

            if ($product) {
                $query->where('products.id', $product->id);
            }

            if (! $rule->conditions) {
                return $query;
            }

            $appliedAttributes = [];

            foreach ($rule->conditions as $condition) {
                if (
                    ! $condition['attribute']
                    || empty($condition['value'])
                    || in_array($condition['attribute'], $appliedAttributes)
                ) {
                    continue;
                }

                $appliedAttributes[] = $condition['attribute'];

                $chunks = explode('|', $condition['attribute']);

                $query = $this->addAttributeToSelect(end($chunks), $query);
            }

            return $query;
        })->get();

        $validatedProductIds = [];

        foreach ($products as $product) {
            if (! $product->getTypeInstance()->priceRuleCanBeApplied()) {
                continue;
            }

            if ($this->validator->validate($rule, $product)) {
                if ($product->getTypeInstance()->isComposite()) {
                    $validatedProductIds = array_merge($validatedProductIds, $product->getTypeInstance()->getChildrenIds());
                } else {
                    $validatedProductIds[] = $product->id;
                }
            }
        }

        return array_unique($validatedProductIds);
    }

    /**
     * Returns catalog rule products
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return \Illuminate\Support\Collection
     */
    public function getCatalogRuleProducts($product = null)
    {
        $ruleProducts = $this->catalogRuleProductRepository->scopeQuery(function ($query) use ($product) {
            $query = $query->distinct()
                ->select('catalog_rule_products.*')
                ->leftJoin('products', 'catalog_rule_products.product_id', '=', 'products.id')
                ->orderBy('channel_id', 'asc')
                ->orderBy('customer_group_id', 'asc')
                ->orderBy('product_id', 'asc')
                ->orderBy('sort_order', 'asc')
                ->orderBy('catalog_rule_id', 'asc');

            $query = $this->addAttributeToSelect('price', $query);

            if (! $product) {
                return $query;
            }

            if (! $product->getTypeInstance()->priceRuleCanBeApplied()) {
                return $query;
            }

            if ($product->getTypeInstance()->isComposite()) {
                $query->whereIn('catalog_rule_products.product_id', $product->getTypeInstance()->getChildrenIds());
            } else {
                $query->where('catalog_rule_products.product_id', $product->id);
            }

            return $query;
        })->get();

        return $ruleProducts;
    }

    /**
     * Add product attribute condition to query
     *
     * @param  string  $attributeCode
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function addAttributeToSelect($attributeCode, $query)
    {
        $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

        if (! $attribute) {
            return $query;
        }

        $query->leftJoin('product_attribute_values as '.'pav_'.$attribute->code, function ($qb) use ($attribute) {
            $qb->where('pav_'.$attribute->code.'.channel', $attribute->value_per_channel ? core()->getDefaultChannelCode() : null)
                ->where('pav_'.$attribute->code.'.locale', $attribute->value_per_locale ? app()->getLocale() : null);

            $qb->on('products.id', 'pav_'.$attribute->code.'.product_id')
                ->where('pav_'.$attribute->code.'.attribute_id', $attribute->id);
        });

        $query->addSelect('pav_'.$attribute->code.'.'.$attribute->column_name.' as '.$attribute->code);

        return $query;
    }
}
