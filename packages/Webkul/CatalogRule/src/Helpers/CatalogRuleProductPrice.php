<?php

namespace Webkul\CatalogRule\Helpers;

use Carbon\Carbon;
use Webkul\CatalogRule\Repositories\CatalogRuleProductPriceRepository;

class CatalogRuleProductPrice
{
    /**
     * Create a new helper instance.
     *
     * @return void
     */
    public function __construct(
        protected CatalogRuleProductPriceRepository $catalogRuleProductPriceRepository,
        protected CatalogRuleProduct $catalogRuleProductHelper
    ) {}

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
            $productKey = $row->product_id.'-'.$row->channel_id.'-'.$row->customer_group_id;

            if (
                $previousKey
                && $previousKey != $productKey
            ) {
                $endRuleFlags = [];

                if (count($prices) > $batchCount) {
                    $this->catalogRuleProductPriceRepository->insert($prices);

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
                    $priceKey = $date->getTimestamp().'-'.$productKey;

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

        $this->catalogRuleProductPriceRepository->insert($prices);
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
     * Clean products price indices
     *
     * @param  array  $productIds
     * @return void
     */
    public function cleanProductPriceIndices($productIds = [])
    {
        if (count($productIds)) {
            $this->catalogRuleProductPriceRepository->whereIn('product_id', $productIds)->delete();
        } else {
            $this->catalogRuleProductPriceRepository->deleteWhere([
                ['product_id', 'like', '%%'],
            ]);
        }
    }
}
