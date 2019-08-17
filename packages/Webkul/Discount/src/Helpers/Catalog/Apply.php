<?php

namespace Webkul\Discount\Helpers\Catalog;

use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;
use Webkul\Discount\Repositories\CatalogRuleProductsRepository as CatalogRuleProducts;
use Webkul\Discount\Repositories\CatalogRuleProductsPriceRepository as CatalogRuleProductsPrice;
use Webkul\Discount\Helpers\Catalog\ConvertXToProductId as ConvertX;
use Webkul\Discount\Helpers\Catalog\Sale;

/**
 * Apply - Applies catalog rule to products intended in the rules
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright 2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Apply extends Sale
{
    /**
     * To hold the catalog rule repository instance
     */
    protected $catalogRule;

    /**
     * To maintain the list of deceased rules and remove their records if present
     */
    protected $activeRules;

    /**
     * To maintain the list of deceased rules and remove their records if present
     */
    protected $deceased;

    /**
     * Holds convertXToProductId instance
     */
    protected $convertX;

    /**
     * Hold catalog rule products repository instance
     */
    protected $catalogRuleProduct;

    /**
     * Hold catalog rule products price repository instance
     */
    protected $catalogRuleProductPrice;

    /**
     * To hold the rule classes
     */
    protected $rules;

    /**
     * @param CatalogRule $catalogRule
     */
    public function __construct(
        CatalogRule $catalogRule,
        ConvertX $convertX,
        CatalogRuleProducts $catalogRuleProduct,
        CatalogRuleProductsPrice $catalogRuleProductPrice
    ) {
        $this->catalogRule = $catalogRule;

        $this->convertX = $convertX;

        $this->catalogRuleProduct = $catalogRuleProduct;

        $this->catalogRuleProductPrice = $catalogRuleProductPrice;

        $this->active = collect();

        $this->activeRules = collect();

        $this->deceased = collect();

        $this->rules = config('discount-rules.catalog');
    }

    /**
     * To apply the new catalog rules and sync previous rules changes,
     */
    public function apply()
    {
        $rules = $this->catalogRule->all();

        foreach ($rules as $rule) {
            $validated = $this->checkApplicability($rule);

            if ($validated) {
                $this->active->push($rule->id);

                $this->activeRules->push($rule);
            } else {
                $this->deceased->push($rule->id);

                // Job execution for deceased rules
            }
        }

        if ($this->active->count()) {
            foreach ($this->activeRules as $rule) {
                $productIDs = $this->getProductIds($rule);

                $this->setSale($rule, $productIDs);
            }
        } else {
            dd($this->deceased);
        }
    }

    /**
     * To set the sale price for products falling catalog rule criteria
     *
     * @param CatalogRule $catalogRule
     *
     * @return Void
     */
    public function setSale($rule, $productIDs)
    {
        dd($productIDs);

        if (is_array($productIDs)) {
            // apply on selected products
            foreach ($productIDs as $productID) {
                $this->createOrUpdate($rule, $productID);

                // $this->catalogRuleProductPrice->createOrUpdate($rule, $productID);
            }
        } else if ($productIDs == '*') {
            $this->catalogRuleProduct->createOrUpdate($rule, $productIDs);

            $this->catalogRuleProductPrice->createOrUpdate($rule, $productIDs);
        }
    }

    /**
     * Create or update catalog rule product resource
     *
     * @param CatalogRule $rule
     * @param Integer $productID
     *
     * @return Void
     */
    public function createOrUpdate($rule, $productID)
    {
        $channels = $rule->channels;
        $customerGroups = $rule->customer_groups;

        $channelsGroupsCross = $channels->crossJoin($customerGroups);

        if ($productID == '*') {
            $products = $this->product->all('id');

            foreach ($channelsGroupsCross as $channelGroup) {
                $channelId = $channelGroup[0]->channel_id;
                $groupId = $channelGroup[1]->customer_group_id;

                $model = new $this->model();

                foreach ($products as $product) {
                    $productID = $product->id;

                    $catalogRuleProduct = $model->where([
                        'channel_id' => $channelId,
                        'customer_group_id' => $groupId,
                        'product_id' => $productID
                    ])->get();

                    if ($catalogRuleProduct->count()) {
                        // check for tie breaker rules and then update
                        $previousRuleID = $catalogRuleProduct->first()->catalog_rule_id;

                        $newRuleID = $rule->id;

                        $winnerRuleId = $this->breakTie($previousRuleID, $newRuleID);

                        $data = [
                            'catalog_rule_id' => $rule->id,
                            'starts_from' => $rule->starts_from,
                            'ends_till' => $rule->ends_till,
                            'customer_group_id' => $groupId,
                            'channel_id' => $channelId,
                            'product_id' => $productID,
                            'action_code' => $rule->action_code,
                            'action_amount' => $rule->discount_amount
                        ];

                        if ($rule->id == $winnerRuleId) {
                            $this->catalogRuleProduct->create($data);
                        } else {
                            $this->catalogRuleProduct->update($data, $catalogRuleProduct->first()->id);
                        }
                    } else {
                        $data = [
                            'catalog_rule_id' => $rule->id,
                            'starts_from' => $rule->starts_from,
                            'ends_till' => $rule->ends_till,
                            'customer_group_id' => $groupId,
                            'channel_id' => $channelId,
                            'product_id' => $productID,
                            'action_code' => $rule->action_code,
                            'action_amount' => $rule->discount_amount
                        ];

                        $this->catalogRuleProduct->create($data);
                    }
                }
            }
        } else {
            foreach ($channelsGroupsCross as $channelGroup) {
                $channelId = $channelGroup[0]->channel_id;
                $groupId = $channelGroup[1]->customer_group_id;

                $model = new $this->model();

                $catalogRuleProduct = $model->where([
                    'channel_id' => $channelId,
                    'customer_group_id' => $groupId,
                    'product_id' => $productID
                ])->get();

                if ($catalogRuleProduct->count()) {
                    // update
                    $catalogRuleProduct->first()->update([
                        'catalog_rule_id' => $rule->id,
                        'starts_from' => $rule->starts_from,
                        'ends_till' => $rule->ends_till,
                        'customer_group_id' => $groupId,
                        'channel_id' => $channelId,
                        'product_id' => $productID,
                        'action_code' => $rule->action_code,
                        'action_amount' => $rule->discount_amount
                    ]);
                } else {
                    // create
                    $this->create([
                        'catalog_rule_id' => $rule->id,
                        'starts_from' => $rule->starts_from,
                        'ends_till' => $rule->ends_till,
                        'customer_group_id' => $groupId,
                        'channel_id' => $channelId,
                        'product_id' => $productID,
                        'action_code' => $rule->action_code,
                        'action_amount' => $rule->discount_amount
                    ]);
                }
            }
        }
    }

    /**
     * To break tie between two rules
     *
     * @param Integer $previousRuleID
     * @param Integer $newRuleID
     *
     * @return String $id
     */
    public function breakTie($previousRuleID, $newRuleID)
    {
        $oldRule = $this->catalogRule->find($previousRuleID);

        $newRule = $this->catalogRule->find($newRuleID);

        dd($oldRule->name, $newRule->name);
    }
}