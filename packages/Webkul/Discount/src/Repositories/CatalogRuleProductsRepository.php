<?php

namespace Webkul\Discount\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Illuminate\Container\Container as App;

/**
 * Catalog Rule Products Reposotory
 *
 * @author  Prashant Singh <prashant.singh852@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class CatalogRuleProductsRepository extends Repository
{
    /**
     * ProductRepository instance
     */
    protected $product;

    public function __construct(Product $product, App $app)
    {
        $this->product = $product;

        parent::__construct($app);
    }

    /**
     * Specify Model class name
     *
     * @return Mixed
     */
    function model()
    {
        return 'Webkul\Discount\Contracts\CatalogRuleProducts';
    }

    /**
     * Create or update catalog rule product resource
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
}
