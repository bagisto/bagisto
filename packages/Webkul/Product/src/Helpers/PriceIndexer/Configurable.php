<?php

namespace Webkul\Product\Helpers\PriceIndexer;

class Configurable extends AbstractPriceIndex
{
    /**
     * Returns product specific pricing for customer group
     *
     * @param  \Webkul\Customer\Contracts\CustomerGroup  $customerGroup
     * @return array
     */
    public function getIndices($customerGroup)
    {
        $this->customerGroup = $customerGroup;

        return [
            'min_price'         => $this->getMinimalPrice() ?? 0,
            'regular_min_price' => $this->getRegularMinimalPrice() ?? 0,
            'max_price'         => $this->getMaximumPrice() ?? 0,
            'regular_max_price' => $this->getRegularMaximumPrice() ?? 0,
        ];
    }

    /**
     * Get product minimal price.
     *
     * @return float
     */
    public function getMinimalPrice($qty = null)
    {
        $minPrices = [];

        foreach ($this->product->variants as $variant) {
            if (! $variant->getTypeInstance()->isSaleable()) {
                continue;
            }

            $variantIndexer = app($variant->getTypeInstance()->getPriceIndexer())
                ->setCustomerGroup($this->customerGroup)
                ->setProduct($variant);

            $minPrices[] = $variantIndexer->getMinimalPrice();
        }

        if (empty($minPrices)) {
            return 0;
        }

        return min($minPrices);
    }
    
    /**
     * Get product regular minimal price.
     *
     * @return float
     */
    public function getRegularMinimalPrice()
    {
        $minPrices = [];

        foreach ($this->product->variants as $variant) {
            if (! $variant->getTypeInstance()->isSaleable()) {
                continue;
            }

            $minPrices[] = $variant->price;
        }

        if (empty($minPrices)) {
            return 0;
        }

        return min($minPrices);
    }

    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        $maxPrices = [];

        foreach ($this->product->variants as $variant) {
            if (! $variant->getTypeInstance()->isSaleable()) {
                continue;
            }

            $variantIndexer = app($variant->getTypeInstance()->getPriceIndexer())
                ->setCustomerGroup($this->customerGroup)
                ->setProduct($variant);

            $maxPrices[] = $variantIndexer->getMinimalPrice();
        }

        if (empty($maxPrices)) {
            return 0;
        }

        return max($maxPrices);
    }

    /**
     * Get product regular maximum price.
     *
     * @return float
     */
    public function getRegularMaximumPrice()
    {
        $maxPrices = [];

        foreach ($this->product->variants as $variant) {
            if (! $variant->getTypeInstance()->isSaleable()) {
                continue;
            }

            $maxPrices[] = $variant->price;
        }

        if (empty($maxPrices)) {
            return 0;
        }

        return max($maxPrices);
    }
}