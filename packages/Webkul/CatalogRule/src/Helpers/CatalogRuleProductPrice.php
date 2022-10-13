<?php

namespace Webkul\CatalogRule\Helpers;

use Carbon\Carbon;
use Webkul\Customer\Repositories\CustomerRepository;
use Webkul\CatalogRule\Repositories\CatalogRuleProductPriceRepository;

class CatalogRuleProductPrice
{
    /**
     * Customer Group instance.
     *
     * @var \Webkul\Customer\Contracts\CustomerGroup
     */
    protected $customerGroup;

    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Attribute\Repositories\CatalogRuleProductPriceRepository  $catalogRuleProductPriceRepository
     * @param  \Webkul\CatalogRule\Repositories\CatalogRuleProduct  $catalogRuleProductHelper
     * @param  \Webkul\Customer\Repositories\CustomerRepository  $customerRepository
     * @return void
     */
    public function __construct(
        protected CatalogRuleProductPriceRepository $catalogRuleProductPriceRepository,
        protected CatalogRuleProduct $catalogRuleProductHelper,
        protected CustomerRepository $customerRepository
    )
    {
    }

    /**
     * Set customer group
     *
     * @param  \Webkul\Customer\Contracts\CustomerGroup  $customerGroup
     * @return \Webkul\Product\Helpers\ProductPriceIndex\AbstractPriceIndex
     */
    public function setCustomerGroup($customerGroup)
    {
        $this->customerGroup = $customerGroup;

        return $this;
    }

    /**
     * Collect discount on cart
     *
     * @param  int  $batchCount
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return void
     */
    public function indexRuleProductPrice($batchCount, $product = null)
    {
        $dates = [
            'current'  => $currentDate = Carbon::now(),
            'previous' => (clone $currentDate)->subDays('1')->setTime(23, 59, 59),
            'next'     => (clone $currentDate)->addDays('1')->setTime(0, 0, 0),
        ];

        $prices = $endRuleFlags = [];

        $previousKey = null;

        $catalogRuleProducts = $this->catalogRuleProductHelper->getCatalogRuleProducts($product);

        foreach ($catalogRuleProducts as $row) {
            $productKey = $row->product_id . '-' . $row->channel_id . '-' . $row->customer_group_id;

            if (
                $previousKey
                && $previousKey != $productKey
            ) {
                $endRuleFlags = [];

                if (count($prices) > $batchCount) {
                    $this->catalogRuleProductPriceRepository->getModel()->insert($prices);

                    $prices = [];
                }
            }

            foreach ($dates as $key => $date) {
                if (
                    (
                        ! $row->starts_from
                        || $date >= $row->starts_from
                    )
                    && (
                        ! $row->ends_till
                        || $date <= $row->ends_till
                    )
                ) {
                    $priceKey = $date->getTimestamp() . '-' . $productKey;

                    if (isset($endRuleFlags[$priceKey])) {
                        continue;
                    }

                    if (! isset($prices[$priceKey])) {
                        $prices[$priceKey] = [
                            'rule_date'         => $date,
                            'catalog_rule_id'   => $row->catalog_rule_id,
                            'channel_id'        => $row->channel_id,
                            'customer_group_id' => $row->customer_group_id,
                            'product_id'        => $row->product_id,
                            'price'             => $this->calculate($row),
                            'starts_from'       => $row->starts_from,
                            'ends_till'         => $row->ends_till,
                        ];
                    } else {
                        $prices[$priceKey]['price'] = $this->calculate($row, $prices[$priceKey]);

                        $prices[$priceKey]['starts_from'] = max($prices[$priceKey]['starts_from'], $row->starts_from);

                        $prices[$priceKey]['ends_till'] = min($prices[$priceKey]['ends_till'], $row->ends_till);
                    }

                    if ($row->end_other_rules) {
                        $endRuleFlags[$priceKey] = true;
                    }
                }
            }

            $previousKey = $productKey;
        }

        $this->catalogRuleProductPriceRepository->getModel()->insert($prices);
    }

    /**
     * Calculates product price based on rule
     *
     * @param  array  $rule
     * @param  \Webkul\Product\Contracts\Product|null  $productData
     * @return float
     */
    public function calculate($rule, $productData = null)
    {
        $price = $productData['price'] ?? $rule->price;

        switch ($rule->action_type) {
            case 'to_fixed':
                $price = min($rule->discount_amount, $price);

                break;

            case 'to_percent':
                $price = $price * $rule->discount_amount / 100;

                break;

            case 'by_fixed':
                $price = max(0, $price - $rule->discount_amount);

                break;

            case 'by_percent':
                $price = $price * (1 - $rule->discount_amount / 100);

                break;
        }

        return $price;
    }

    /**
     * Clean product price index
     *
     * @param  array  $productIds
     * @return void
     */
    public function cleanProductPriceIndex($productIds = [])
    {
        if (count($productIds)) {
            $this->catalogRuleProductPriceRepository->getModel()->whereIn('product_id', $productIds)->delete();
        } else {
            $this->catalogRuleProductPriceRepository->deleteWhere([
                ['product_id', 'like', '%%']
            ]);
        }
    }

    /**
     * Get catalog rules product price for specific date, channel and customer group.
     *
     * @param  \Webkul\Product\Contracts\Product  $product
     * @return array|void
     */
    public function getRulePrice($product)
    {
        if (! $this->customerGroup) {
            $this->customerGroup = $this->customerRepository->getCurrentGroup();
        }

        return $this->catalogRuleProductPriceRepository->checkInLoadedCatalogRulePrice($product, $this->customerGroup->id);
    }
}