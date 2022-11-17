<?php

namespace Webkul\Product\Helpers\Indexers\Price;

class Bundle extends AbstractType
{
    /**
     * Returns product specific pricing for customer group
     *
     * @return array
     */
    public function getIndices()
    {
        return [
            'min_price'         => $this->getMinimalPrice() ?? 0,
            'regular_min_price' => $this->getRegularMinimalPrice() ?? 0,
            'max_price'         => $this->getMaximumPrice() ?? 0,
            'regular_max_price' => $this->getRegularMaximumPrice() ?? 0,
            'product_id'        => $this->product->id,
            'customer_group_id' => $this->customerGroup->id,
        ];
    }

    /**
     * Get product minimal price.
     *
     * @param  int  $qty
     * @return float
     */
    public function getMinimalPrice($qty = null)
    {
        $minPrice = 0;

        $haveRequiredOptions = $this->haveRequiredOptions();

        $minPrices = [];

        foreach ($this->product->bundle_options as $option) {
            $optionProductsPrices = $this->getOptionProductsPrices($option);

            if (! count($optionProductsPrices)) {
                continue;
            }

            $selectionMinPrice = min($optionProductsPrices);

            if ($option->is_required) {
                $minPrice += $selectionMinPrice;
            } elseif (! $haveRequiredOptions) {
                $minPrices[] = $selectionMinPrice;
            }
        }

        if (! $haveRequiredOptions) {
            $minPrice = count($minPrices) ? min($minPrices) : 0;
        }

        return $minPrice;
    }

    /**
     * Get product regular minimal price.
     *
     * @return float
     */
    public function getRegularMinimalPrice()
    {
        $minPrice = 0;

        $haveRequiredOptions = $this->haveRequiredOptions();

        $minPrices = [];

        foreach ($this->product->bundle_options as $option) {
            $optionProductsPrices = $this->getOptionProductsPrices($option, false);

            if (! count($optionProductsPrices)) {
                continue;
            }

            $selectionMinPrice = min($optionProductsPrices);

            if ($option->is_required) {
                $minPrice += $selectionMinPrice;
            } elseif (! $haveRequiredOptions) {
                $minPrices[] = $selectionMinPrice;
            }
        }

        if (
            ! $haveRequiredOptions
            && count($minPrices)
        ) {
            $minPrice = min($minPrices);
        }

        return $minPrice;
    }

    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        $optionPrices = [];

        foreach ($this->product->bundle_options as $option) {
            foreach ($option->bundle_option_products as $bundleOptionProduct) {
                $variant = $bundleOptionProduct->product;

                if (! $variant->getTypeInstance()->isSaleable()) {
                    continue;
                }

                $variantIndexer = $variant->getTypeInstance()
                    ->getPriceIndexer()
                    ->setCustomerGroup($this->customerGroup)
                    ->setProduct($variant);

                if (in_array($option->type, ['multiselect', 'checkbox'])) {
                    if (! isset($optionPrices[$option->id][0])) {
                        $optionPrices[$option->id][0] = 0;
                    }

                    $optionPrices[$option->id][0] += $bundleOptionProduct->qty * $variantIndexer->getMinimalPrice();
                } else {
                    $optionPrices[$option->id][] = $bundleOptionProduct->qty * $variantIndexer->getMinimalPrice();
                }

            }
        }

        $maxPrice = 0;

        foreach ($optionPrices as $optionPrice) {
            $maxPrice += max($optionPrice);
        }

        return $maxPrice;
    }

    /**
     * Get product regular maximum price.
     *
     * @return float
     */
    public function getRegularMaximumPrice()
    {
        $optionPrices = [];

        foreach ($this->product->bundle_options as $option) {
            foreach ($option->bundle_option_products as $index => $bundleOptionProduct) {
                $variant = $bundleOptionProduct->product;

                if (! $variant->getTypeInstance()->isSaleable()) {
                    continue;
                }

                if (in_array($option->type, ['multiselect', 'checkbox'])) {
                    if (! isset($optionPrices[$option->id][0])) {
                        $optionPrices[$option->id][0] = 0;
                    }

                    $optionPrices[$option->id][0] += $bundleOptionProduct->qty * $variant->price;
                } else {
                    $optionPrices[$option->id][] = $bundleOptionProduct->qty * $variant->price;
                }

            }
        }

        $maxPrice = 0;

        foreach ($optionPrices as $key => $optionPrice) {
            $maxPrice += max($optionPrice);
        }

        return $maxPrice;
    }

    /**
     * Check if product has required options or not.
     *
     * @return bool
     */
    protected function haveRequiredOptions()
    {
        foreach ($this->product->bundle_options as $option) {
            if ($option->is_required) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get product regular minimal price.
     *
     * @param  \Webkul\Product\Contracts\ProductBundleOption  $option
     * @param  bool  $minPrice
     * @return float
     */
    public function getOptionProductsPrices($option, $minPrice = true)
    {
        $optionPrices = [];

        foreach ($option->bundle_option_products as $bundleOptionProduct) {
            $variant = $bundleOptionProduct->product;

            if (! $variant->getTypeInstance()->isSaleable()) {
                continue;
            }

            $variantIndexer = $variant->getTypeInstance()
                ->getPriceIndexer()
                ->setCustomerGroup($this->customerGroup)
                ->setProduct($variant);

            $optionPrices[] = $bundleOptionProduct->qty
                * ($minPrice
                    ? $variantIndexer->getMinimalPrice()
                    : $variant->price
                );
        }

        return $optionPrices;
    }
}