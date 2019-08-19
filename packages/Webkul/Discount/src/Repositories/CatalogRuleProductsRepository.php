<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Illuminate\Container\Container as App;

/**
 * CatalogRuleProductsRepository
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleProductsRepository extends Repository
{
    /**
     * ProductRepository instance
     */
    protected $product;

    /**
     * CatalogRule Apply instance
     */
    protected $apply;

    /**
     * @param Product $product
     * @param App $app
     * @param Apply $apply
     */
    public function __construct(Product $product, App $app)
    {
        $this->product = $product;

        parent::__construct($app);
    }

    public function getDiscountAmount($product, $rule)
    {

        $actionClass = config('discount-rules.catalog')[$rule->action_code];

        $actionInstance = new $actionClass();

        $discountAmount = $actionInstance->calculate($rule, $product);

        return $discountAmount;
    }

    /**
     * Specify Model class name
     *
     * @return String
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CatalogRuleProducts';
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

                    $catalogRuleProduct = $this->findWhere([
                        'channel_id' => $channelId,
                        'customer_group_id' => $groupId,
                        'product_id' => $productID
                    ]);

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

                $catalogRuleProduct = $this->findWhere([
                    'channel_id' => $channelId,
                    'customer_group_id' => $groupId,
                    'product_id' => $productID
                ]);

                if ($catalogRuleProduct->count() && $catalogRuleProduct->first()->catalog_rule_id != $rule->id) {
                    $product = $this->product->find($productID);
                    $productPrice = $product->price;

                    $discountAmount = $this->getDiscountAmount($product, $rule);

                    // check for tie breaker rules and then update
                    $previousRuleID = $catalogRuleProduct->first()->catalog_rule_id;

                    $newRuleID = $rule->id;

                    $winnerRuleId = $this->breakTie($previousRuleID, $newRuleID);

                    // update
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
                } else if ($catalogRuleProduct->count() == 0) {
                    $product = $this->product->find($productID);
                    $productPrice = $product->price;

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

                    $this->create($data);
                } else {
                    // do the reassessment updation if the cart rule action changes the new action and its amount needs to be updated
                }
            }
        }
    }
}
