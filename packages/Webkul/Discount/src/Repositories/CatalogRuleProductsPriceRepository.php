<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Discount\Repositories\CatalogRuleProductsRepository as CatalogRuleProduct;
use Illuminate\Container\Container as App;

/**
 * CatalogRuleProductsPriceRepository
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com> @prashant-webkul
 * @copyright  2019 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleProductsPriceRepository extends Repository
{
    /**
     * To hold ProductRepository instance
     */
    protected $product;

    /**
     * To hold CatalogRuleProductRepository instance
     *
     */
    protected $catalogRuleProduct;

    /**
     * @param Product $product
     */
    public function __construct(Product $product, CatalogRuleProduct $catalogRuleProduct,App $app)
    {

        $this->product = $product;

        $this->catalogRuleProduct = $catalogRuleProduct;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return String
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CatalogRuleProductsPrice';
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

                foreach ($products as $product) {
                    $productID = $product->id;

                    $catalogRuleProductPrice = $this->findWhere([
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

                        $this->update($data, $catalogRuleProductPrice->first()->id);
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

                        $this->create($data);
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

                $catalogRuleProductPrice = $this->findWhere([
                    'channel_id' => $channelId,
                    'customer_group_id' => $groupId,
                    'product_id' => $productID
                ]);

                if ($catalogRuleProductPrice->count() && $catalogRuleProductPrice->first()->catalog_rule_id != $rule->id) {
                    $catalogRuleProduct = $catalogRuleProduct->first();

                    $discountAmount = $rule->discount_amount;

                    $product = $this->product->find($productID);

                    $productPrice = $product->price - $catalogRuleProduct->action_amount;

                    $data = [
                        'catalog_rule_id' => $rule->id,
                        'starts_from' => $rule->starts_from,
                        'ends_till' => $rule->ends_till,
                        'customer_group_id' => $groupId,
                        'channel_id' => $channelId,
                        'product_id' => $productID,
                        'rule_price' => $productPrice
                    ];

                    // update
                    $catalogRuleProductPrice->first()->update($data);
                } else if ($catalogRuleProductPrice->count() == 0) {
                    $catalogRuleProduct = $catalogRuleProduct->first();

                    $discountAmount = $rule->discount_amount;

                    $product = $this->product->find($productID);

                    $productPrice = $product->price - $catalogRuleProduct->action_amount;

                    if ($productPrice <= $discountAmount) {
                        $product->price = $productPrice - $discountAmount;
                    } else {
                        $product->price = 0;
                    }

                    $data = [
                        'catalog_rule_id' => $rule->id,
                        'starts_from' => $rule->starts_from,
                        'ends_till' => $rule->ends_till,
                        'customer_group_id' => $groupId,
                        'channel_id' => $channelId,
                        'product_id' => $productID,
                        'rule_price' => $productPrice
                    ];

                    $this->create($data);
                } else {
                    // do the reassessment updation if the cart rule action changes the new action and its amount needs to be updated
                }
            }
        }
    }
}