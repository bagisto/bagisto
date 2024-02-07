<?php

namespace Webkul\CatalogRule\Helpers;

use Carbon\Carbon;
use Webkul\CatalogRule\Repositories\CatalogRuleProductPriceRepository;

class CatalogRuleProductPrice
{
    /**
     * Create a new helper instance.
     *
     * @param  \Webkul\Attribute\Repositories\CatalogRuleProductPriceRepository  $catalogRuleProductPriceRepository
     * @return void
     */
    public function __construct(
        protected CatalogRuleProductPriceRepository $catalogRuleProductPriceRepository,
        protected CatalogRuleProduct $catalogRuleProductHelper
    ) {
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

        foreach ($catalogRuleProducts as $catalogRuleProduct) {
            $productKey = $catalogRuleProduct->product_id.'-'.$catalogRuleProduct->channel_id.'-'.$catalogRuleProduct->customer_group_id;

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

            foreach ($dates as $date) {
                if (
                    (
                        ! $catalogRuleProduct->starts_from
                        || $date >= $catalogRuleProduct->starts_from
                    )
                    && (
                        ! $catalogRuleProduct->ends_till
                        || $date <= $catalogRuleProduct->ends_till
                    )
                ) {
                    $priceKey = $date->getTimestamp().'-'.$productKey;

                    if (isset($endRuleFlags[$priceKey])) {
                        continue;
                    }

                    if (! isset($prices[$priceKey])) {
                        $prices[$priceKey] = [
                            'rule_date'         => $date,
                            'catalog_rule_id'   => $catalogRuleProduct->catalog_rule_id,
                            'channel_id'        => $catalogRuleProduct->channel_id,
                            'customer_group_id' => $catalogRuleProduct->customer_group_id,
                            'product_id'        => $catalogRuleProduct->product_id,
                            'price'             => $this->calculate($catalogRuleProduct),
                            'starts_from'       => $catalogRuleProduct->starts_from,
                            'ends_till'         => $catalogRuleProduct->ends_till,
                        ];
                    } else {
                        $prices[$priceKey]['price'] = $this->calculate($catalogRuleProduct, $prices[$priceKey]);

                        $prices[$priceKey]['starts_from'] = max($prices[$priceKey]['starts_from'], $catalogRuleProduct->starts_from);

                        $prices[$priceKey]['ends_till'] = min($prices[$priceKey]['ends_till'], $catalogRuleProduct->ends_till);
                    }

                    if ($catalogRuleProduct->end_other_rules) {
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
     * @param  \Webkul\CatalogRule\Models\CatalogRuleProduct  $catalogRuleProduct
     * @param  \Webkul\Product\Contracts\Product|null  $productData
     * @return float
     */
    public function calculate($catalogRuleProduct, $productData = null)
    {
        if (! $catalogRuleProduct->catalog_rule_status) {
            return $catalogRuleProduct->price;
        }

        $price = $productData['price'] ?? $catalogRuleProduct->price;

        switch ($catalogRuleProduct->action_type) {
            case 'to_fixed':
                $price = min($catalogRuleProduct->discount_amount, $price);

                break;

            case 'to_percent':
                $price = $price * $catalogRuleProduct->discount_amount / 100;

                break;

            case 'by_fixed':
                $price = max(0, $price - $catalogRuleProduct->discount_amount);

                break;

            case 'by_percent':
                $price = $price * (1 - $catalogRuleProduct->discount_amount / 100);

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
