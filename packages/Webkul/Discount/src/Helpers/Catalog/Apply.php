<?php

namespace Webkul\Discount\Helpers\Catalog;

use Webkul\Discount\Repositories\CatalogRuleRepository as CatalogRule;
use Webkul\Discount\Repositories\CatalogRuleProductsRepository as CatalogRuleProducts;
use Webkul\Discount\Repositories\CatalogRuleProductsPriceRepository as CatalogRuleProductsPrice;
use Webkul\Discount\Helpers\Catalog\ConvertXToProductId as ConvertX;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Discount\Helpers\Catalog\Sale;

/**
 * Apply - Applies catalog rule to products intended in the rules
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
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
     * Hold ProductRepository instance
     */
    protected $product;

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
        CatalogRuleProductsPrice $catalogRuleProductPrice,
        Product $product
    ) {
        $this->catalogRule = $catalogRule;

        $this->convertX = $convertX;

        $this->catalogRuleProduct = $catalogRuleProduct;

        $this->catalogRuleProductPrice = $catalogRuleProductPrice;

        $this->product = $product;

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
            $productIDs = array();

            foreach ($this->activeRules as $rule) {
                $productIDs = $this->getProductIds($rule);

                if ($productIDs) {
                    $productIDs = $this->expandProducts($productIDs);

                    $this->setSale($rule, $productIDs);
                }
            }
        } else {
            // handle the deceased rules here or call the declutter handler over here
            // dd($this->deceased);
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
        if (is_array($productIDs)) {
            // apply on selected products
            foreach ($productIDs as $productID) {
                // catalog rule product resource is updated here
                $this->createOrUpdateCatalogRuleProduct($rule, $productID);

                // catalog rule product price resource is updated here
                $this->createOrUpdateCatalogRuleProductPrice($rule, $productID);
            }
        } else if ($productIDs == '*') {
            $this->catalogRuleProduct->createOrUpdateCatalogRuleProduct($rule, $productIDs);

            $this->catalogRuleProductPrice->createOrUpdateCatalogRuleProductPrice($rule, $productIDs);
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
    public function createOrUpdateCatalogRuleProduct($rule, $productID)
    {
        $channels = $rule->channels;
        $customerGroups = $rule->customer_groups;

        $channelsGroupsCross = $channels->crossJoin($customerGroups);

        if ($productID == '*') {
            $products = $this->product->all('id');

            foreach ($channelsGroupsCross as $channelGroup) {
                $channelId = $channelGroup[0]->channel_id;
                $groupId = $channelGroup[1]->customer_group_id;

                foreach ($products as $product) {
                    $productID = $product->id;

                    $catalogRuleProduct = $this->catalogRuleProduct->findWhere([
                        'channel_id' => $channelId,
                        'customer_group_id' => $groupId,
                        'product_id' => $productID
                    ]);

                    if ($catalogRuleProduct->count()) {
                        // check for tie breaker rules and then update
                        $product = $this->product->find($productID);

                        $productPrice = $product->price;

                        // check for tie breaker rules and then update
                        $previousRuleID = $catalogRuleProduct->first()->catalog_rule_id;

                        $newRuleID = $rule->id;

                        $winnerRuleId = $this->breakTie($previousRuleID, $newRuleID, $product);

                        $discountAmount = $this->getDiscountAmount($product, $rule);

                        $data = [
                            'catalog_rule_id' => $rule->id,
                            'starts_from' => $rule->starts_from,
                            'ends_till' => $rule->ends_till,
                            'customer_group_id' => $groupId,
                            'channel_id' => $channelId,
                            'product_id' => $productID,
                            'action_code' => $rule->action_code,
                            'action_amount' => $discountAmount
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

                $catalogRuleProduct = $this->catalogRuleProduct->findWhere([
                    'channel_id' => $channelId,
                    'customer_group_id' => $groupId,
                    'product_id' => $productID
                ]);

                if ($catalogRuleProduct->count()) {
                    $product = $this->product->find($productID);
                    $productPrice = $product->price;

                    // check for tie breaker rules and then update
                    $previousRuleID = $catalogRuleProduct->first()->catalog_rule_id;

                    $newRuleID = $rule->id;

                    $winnerRuleId = $this->breakTie($previousRuleID, $newRuleID, $product);

                    // update
                    if ($winnerRuleId != $rule->id) {
                        $discountAmount = $this->getDiscountAmount($product, $rule);

                        $catalogRuleProduct->first()->update([
                            'catalog_rule_id' => $rule->id,
                            'starts_from' => $rule->starts_from,
                            'ends_till' => $rule->ends_till,
                            'customer_group_id' => $groupId,
                            'channel_id' => $channelId,
                            'product_id' => $productID,
                            'action_code' => $rule->action_code,
                            'action_amount' => $discountAmount
                        ]);
                    } else {
                        //do reassessment
                    }
                } else if ($catalogRuleProduct->count() == 0) {
                    $product = $this->product->find($productID);

                    $discountAmount = $this->getDiscountAmount($product, $rule);

                    $data = [
                        'catalog_rule_id' => $rule->id,
                        'starts_from' => $rule->starts_from,
                        'ends_till' => $rule->ends_till,
                        'customer_group_id' => $groupId,
                        'channel_id' => $channelId,
                        'product_id' => $productID,
                        'action_code' => $rule->action_code,
                        'action_amount' => $discountAmount
                    ];

                    $this->catalogRuleProduct->create($data);
                } else {
                    // do the reassessment updation if the cart rule action changes the new action and its amount needs to be updated
                }
            }
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
    public function createOrUpdateCatalogRuleProductPrice($rule, $productID)
    {
        $channels = $rule->channels;
        $customerGroups = $rule->customer_groups;

        $channelsGroupsCross = $channels->crossJoin($customerGroups);

        if ($productID == '*') {
            $products = $this->product->all('id');

            foreach ($channelsGroupsCross as $channelGroup) {
                $channelId = $channelGroup[0]->channel_id;
                $groupId = $channelGroup[1]->customer_group_id;

                foreach ($products as $product) {
                    $productID = $product->id;

                    $catalogRuleProductPrice = $this->catalogRuleProductPrice->findWhere([
                        'channel_id' => $channelId,
                        'customer_group_id' => $groupId,
                        'product_id' => $productID
                    ]);

                    if ($catalogRuleProductPrice->count()) {
                        // check for tie breaker rules and then update
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

                        $this->catalogRuleProductPrice->update($data, $catalogRuleProductPrice->first()->id);
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

                        $this->catalogRuleProductPrice->create($data);
                    }
                }
            }
        } else {
            foreach ($channelsGroupsCross as $channelGroup) {
                $channelId = $channelGroup[0]->channel_id;
                $groupId = $channelGroup[1]->customer_group_id;

                $catalogRuleProduct = $this->catalogRuleProduct->findWhere([
                    'channel_id' => $channelId,
                    'customer_group_id' => $groupId,
                    'product_id' => $productID
                ]);

                $catalogRuleProductPrice = $this->catalogRuleProductPrice->findWhere([
                    'channel_id' => $channelId,
                    'customer_group_id' => $groupId,
                    'product_id' => $productID
                ]);

                if ($catalogRuleProductPrice->count()) {
                    $catalogRuleProduct = $catalogRuleProduct->first();

                    $product = $this->product->find($productID);

                    $productPrice = $product->price - $catalogRuleProduct->action_amount;

                    $winnerRuleId = $this->breakTie($catalogRuleProduct->catalog_rule_id, $rule->id, $product);

                    if ($winnerRuleId != $rule->id) {
                        $data = [
                            'catalog_rule_id' => $rule->id,
                            'starts_from' => $rule->starts_from,
                            'ends_till' => $rule->ends_till,
                            'customer_group_id' => $groupId,
                            'channel_id' => $channelId,
                            'product_id' => $productID,
                            'rule_price' => $productPrice
                        ];

                        $catalogRuleProductPrice->first()->update($data);
                    } else {
                        // do reassement
                    }
                } else if ($catalogRuleProductPrice->count() == 0) {
                    $catalogRuleProduct = $catalogRuleProduct->first();

                    $discountAmount = $catalogRuleProduct->action_amount;

                    $product = $this->product->find($productID);

                    $productPrice = $product->price - $discountAmount;

                    $data = [
                        'catalog_rule_id' => $rule->id,
                        'starts_from' => $rule->starts_from,
                        'ends_till' => $rule->ends_till,
                        'customer_group_id' => $groupId,
                        'channel_id' => $channelId,
                        'product_id' => $productID,
                        'rule_price' => $productPrice
                    ];

                    $this->catalogRuleProductPrice->create($data);
                } else {
                    // do the reassessment updation if the cart rule action changes the new action and its amount needs to be updated
                }
            }
        }
    }

    /**
     *  Get discount amount for the rule and product
     *
     * @return Decimal $discountAmount
     */
    public function getDiscountAmount($product, $rule)
    {

        $actionClass = config('discount-rules.catalog')[$rule->action_code];

        $actionInstance = new $actionClass();

        $discountAmount = $actionInstance->calculate($rule, $product);

        return $discountAmount;
    }

    /**
     * To expand the productIDs of configurable products
     *
     * @param Array $productIDs
     *
     * @return Array
     */
    protected function expandProducts($productIDs)
    {
        $products = app('Webkul\Product\Repositories\ProductRepository');

        $newProductIDs = collect();

        foreach ($productIDs as $productID) {
            $product = $products->find($productID);

            if ($product->type == 'configurable') {
                $variants = $product->variants;

                foreach($variants as $variant) {
                    $newProductIDs->push($variant->id);
                }
            } else {
                $newProductIDs->push($productID);
            }
        }

        if ($newProductIDs->count()) {
            return $newProductIDs->toArray();
        } else {
            return [];
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
    public function breakTie($previousRuleID, $newRuleID, $product)
    {
        $oldRule = $this->catalogRule->find($previousRuleID);

        $newRule = $this->catalogRule->find($newRuleID);

        if ($oldRule->ends_other_rules) {
            return $oldRule->id;
        } else {
            $oldRuleDiscount = $this->getDiscountAmount($product, $oldRule);

            $newRuleDiscount = $this->getDiscountAmount($product, $newRule);

            if ($newRuleDiscount > $oldRuleDiscount) {
                return $newRule->id;
            } else {
                return $oldRule->id;
            }
        }
    }

    /**
     * To declutter the catalog rules that are either inactive or deleted
     *
     * @return Boolean
     */
    public function deClutter()
    {
        $rules = $this->catalogRule->all();

        foreach ($rules as $rule) {
            $validated = $this->checkApplicability($rule);

            if (! $validated) {
                $this->deceased->push($rule->id);
            }
        }

        if (count($this->deceased)) {
            $count = 0;

            foreach ($this->deceased as $deceased)  {
                $cartRuleProducts = $this->catalogRuleProduct->findWhere([
                    'catalog_rule_id' => $deceased
                ]);

                $cartRuleProductsPrice = $this->catalogRuleProductPrice->findWhere([
                    'catalog_rule_id' => $deceased
                ]);

                // obvious logic for removing entries as entries in both storage needs to be exactly equal for a product
                foreach ($cartRuleProducts as $cartRuleProduct) {
                    // deletes cartRuleProducts resource
                    $cartRuleProduct->delete();

                    // deletes cartRuleProductsPrice resource
                    $cartRuleProductsPrice->delete();
                }
            }

            return true;
        } else {
            return false;
        }
    }
}