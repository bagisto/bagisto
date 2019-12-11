<?php

namespace Webkul\CatalogRule\Helpers;

use Carbon\Carbon;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\CatalogRule\Repositories\CatalogRuleRepository;
use Webkul\CatalogRule\Repositories\CatalogRuleProductRepository;
use Webkul\Rule\Helpers\Validator;

class CatalogRule
{
    /**
     * ProductRepository object
     *
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * CatalogRuleRepository object
     *
     * @var CatalogRuleRepository
     */
    protected $catalogRuleRepository;

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
     * @param  Webkul\Product\Repositories\ProductRepository                $productRepository
     * @param  Webkul\CatalogRule\Repositories\CatalogRuleRepository        $catalogRuleRepository
     * @param  Webkul\CatalogRule\Repositories\CatalogRuleProductRepository $catalogRuleProductRepository
     * @param  Webkul\Rule\Helpers\Validator                                $validator
     * @return void
     */
    public function __construct(
        ProductRepository $productRepository,
        CatalogRuleRepository $catalogRuleRepository,
        CatalogRuleProductRepository $catalogRuleProductRepository,
        Validator $validator
    )
    {
        $this->productRepository = $productRepository;

        $this->catalogRuleRepository = $catalogRuleRepository;

        $this->catalogRuleProductRepository = $catalogRuleProductRepository;

        $this->validator = $validator;
    }

    /**
     * Returns catalog rules
     *
     * @return Collection
     */
    public function getCatalogRules()
    {
        static $catalogRules;

        if ($catalogRules)
            return $catalogRules;

        $catalogRules = $this->catalogRuleRepository->scopeQuery(function($query) {
            return $query->where(function ($query1) {
                        $query1->where('catalog_rules.starts_from', '<=', Carbon::now()->format('Y-m-d'))->orWhereNull('catalog_rules.starts_from');
                    })
                    ->where(function ($query2) {
                        $query2->where('catalog_rules.ends_till', '>=', Carbon::now()->format('Y-m-d'))->orWhereNull('catalog_rules.ends_till');
                    })
                    ->orderBy('sort_order', 'asc');
        })->findWhere(['status' => 1]);

        return $catalogRules;
    }

    /**
     * Apply all price rules to all products
     *
     * @return void
     */
    public function applyAll()
    {
        foreach ($this->getCatalogRules() as $rule) {
            $this->insertRulePrice($rule);
        }
    }

    /**
     * Get array of product ids which are matched by rule
     *
     * @param CatalogRule $rule
     * @return array
     */
    public function getMatchingProductIds($rule)
    {
        $qb = $this->productRepository->scopeQuery(function($query) use($rule) {
            $qb = $query->distinct()
                    ->addSelect('products.*')
                    ->leftJoin('product_flat', 'products.id', '=', 'product_flat.product_id')
                    ->leftJoin('channels', 'product_flat.channel', '=', 'channels.code')
                    ->where('product_flat.status', 1)
                    ->whereIn('channels.id', $rule->channels()->pluck('id')->toArray());

            foreach ($rule->conditions as $condition) {
                if (! $condition['attribute'] || ! $condition['value'])
                    continue;

                $qb = $this->addAttributeToSelect($condition, $qb);
            }

            return $qb;
        });

        return $qb->get()->pluck('id')->toArray();
    }

    /**
     * Add product attribute condition to query
     *
     * @param array        $attributeCondition
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    public function addAttributeToSelect($attributeCondition, $query)
    {

    }

    /**
     * Collect discount on cart
     *
     * @param CatalogRule $rule
     * @param integer     $batchCount
     * @return void
     */
    public function insertRulePrice($rule, $batchCount = 1000)
    {
        $productIds = $this->getMatchingProductIds($rule);

        return;
        dd($productIds);

        $rows = [];

        foreach ($productIds as $productId) {
            foreach ($rule->channels()->pluck('id') as $channelId) {
                foreach ($rule->customer_groups()->pluck('id') as $customerGroupId) {
                    $rows[] = [
                        'starts_from' => $rule->starts_from,
                        'ends_till' => $rule->starts_from,
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
}