<?php

namespace Webkul\CatalogRule\Helpers;

use Carbon\Carbon;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\CatalogRule\Repositories\CatalogRuleProductRepository;
use Webkul\Rule\Helpers\Validator;

class CatalogRuleProduct
{
    /**
     * AttributeRepository object
     *
     * @var AttributeRepository
     */
    protected $attributeRepository;

    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * CatalogRuleProductRepository object
     *
     * @var CatalogRuleProductRepository
     */
    protected $catalogRuleProductRepository;

    /**
     * Validator object
     *
     * @var Validator
     */
    protected $validator;

    /**
     * Create a new helper instance.
     *
     * @param  Webkul\Attribute\Repositories\AttributeRepository            $attributeRepository
     * @param  Webkul\Product\Repositories\ProductRepository                $productRepository
     * @param  Webkul\CatalogRule\Repositories\CatalogRuleProductRepository $catalogRuleProductRepository
     * @param  Webkul\Rule\Helpers\Validator                                $validator
     * @return void
     */
    public function __construct(
        AttributeRepository $attributeRepository,
        ProductRepository $productRepository,
        CatalogRuleProductRepository $catalogRuleProductRepository,
        Validator $validator
    )
    {
        $this->attributeRepository = $attributeRepository;

        $this->productRepository = $productRepository;

        $this->catalogRuleProductRepository = $catalogRuleProductRepository;

        $this->validator = $validator;
    }

    /**
     * Collect discount on cart
     *
     * @param CatalogRule $rule
     * @param integer     $batchCount
     * @return void
     */
    public function insertRuleProduct($rule, $batchCount = 1000, $product = null)
    {
        $productIds = $this->getMatchingProductIds($rule, $product);

        $rows = [];

        $startsFrom = $rule->starts_from ? Carbon::createFromTimeString($rule->starts_from . " 00:00:01") : null;

        $endsTill = $rule->ends_till ? Carbon::createFromTimeString($rule->ends_till . " 23:59:59") : null;

        foreach ($productIds as $productId) {
            foreach ($rule->channels()->pluck('id') as $channelId) {
                foreach ($rule->customer_groups()->pluck('id') as $customerGroupId) {
                    $rows[] = [
                        'starts_from' => $startsFrom,
                        'ends_till' => $endsTill,
                        'catalog_rule_id' => $rule->id,
                        'channel_id' => $channelId,
                        'customer_group_id' => $customerGroupId,
                        'product_id' => $productId,
                        'discount_amount' => $rule->discount_amount,
                        'action_type' => $rule->action_type,
                        'end_other_rules' => $rule->end_other_rules,
                        'sort_order' => $rule->sort_order,
                    ];

                    if (count($rows) == $batchCount) {
                        $this->catalogRuleProductRepository->getModel()->insert($rows);

                        $rows = [];
                    }
                }
            }
        }

        if (! empty($rows))
            $this->catalogRuleProductRepository->getModel()->insert($rows);
    }

    /**
     * Get array of product ids which are matched by rule
     *
     * @param CatalogRule $rule
     * @param Product     $product
     * @return array
     */
    public function getMatchingProductIds($rule, $product = null)
    {
        $qb = $this->productRepository->scopeQuery(function($query) use($rule, $product) {
            $qb = $query->distinct()
                    ->addSelect('products.*')
                    ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
                    ->leftJoin('channels', 'product_flat.channel', '=', 'channels.code')
                    ->where('product_flat.status', 1)
                    ->whereIn('channels.id', $rule->channels()->pluck('id')->toArray());

            if ($product)
                $qb->where('products.id', $product->id);

            if (! $rule->conditions)
                return $qb;

            $appliedAttributes = [];

            foreach ($rule->conditions as $condition) {
                if (! $condition['attribute']
                    || ! isset($condition['value'])
                    || is_null($condition['value'])
                    || $condition['value'] == ''
                    || in_array($condition['attribute'], $appliedAttributes))
                    continue;
                
                $appliedAttributes[] = $condition['attribute'];

                $chunks = explode('|', $condition['attribute']);

                $qb = $this->addAttributeToSelect(end($chunks), $qb);
            }

            return $qb;
        });

        $validatedProductIds = [];

        foreach ($qb->get() as $product) {
            if (! $product->getTypeInstance()->priceRuleCanBeApplied())
                continue;

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
     * Add product attribute condition to query
     *
     * @param string       $attributeCode
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    public function addAttributeToSelect($attributeCode, $query)
    {
        $attribute = $this->attributeRepository->findOneByField('code', $attributeCode);

        if (! $attribute)
            return $query;

        $query = $query->leftJoin('product_attribute_values as ' . 'pav_' . $attribute->code, function($qb) use($attribute) {
            $qb = $qb->where('pav_' . $attribute->code . '.channel', $attribute->value_per_channel ? core()->getDefaultChannelCode() : null)
                    ->where('pav_' . $attribute->code . '.locale', $attribute->value_per_locale ? app()->getLocale() : null);
            
            $qb->on('products.id', 'pav_' . $attribute->code . '.product_id')
                    ->where('pav_' . $attribute->code . '.attribute_id', $attribute->id);
        });

        $query = $query->addSelect('pav_' . $attribute->code . '.' . ProductAttributeValue::$attributeTypeFields[$attribute->type] . ' as ' . $attribute->code);

        return $query;
    }

    /**
     * Returns catalog rule products
     *
     * @param Product $product
     * @return Collection
     */
    public function getCatalogRuleProducts($product = null)
    {
        $results = $this->catalogRuleProductRepository->scopeQuery(function($query) use($product) {
            $qb = $query->distinct()
                    ->select('catalog_rule_products.*')
                    ->leftJoin('product_flat', 'catalog_rule_products.product_id', '=', 'product_flat.product_id')
                    ->where('product_flat.status', 1)
                    ->addSelect('product_flat.price')
                    ->orderBy('channel_id', 'asc')
                    ->orderBy('customer_group_id', 'asc')
                    ->orderBy('product_id', 'asc')
                    ->orderBy('sort_order', 'asc')
                    ->orderBy('catalog_rule_id', 'asc');

            if ($product) {

                if (! $product->getTypeInstance()->priceRuleCanBeApplied())
                    return $qb;

                if ($product->getTypeInstance()->isComposite()) {
                    $qb->whereIn('catalog_rule_products.product_id', $product->getTypeInstance()->getChildrenIds());
                } else {
                    $qb->where('catalog_rule_products.product_id', $product->id);
                }
            }

            return $qb;
        })->get();

        return $results;
    }

    /**
     * Returns catalog rules
     *
     * @param CatalogRule $rule
     * @return void
     */
    public function cleanProductIndex($productIds = [])
    {
        if (count($productIds)) {
            $this->catalogRuleProductRepository->getModel()->whereIn('product_id', $productIds)->delete();
        } else {
            $this->catalogRuleProductRepository->deleteWhere([
                ['product_id', 'like', '%%']
            ]);
        }
    }
}