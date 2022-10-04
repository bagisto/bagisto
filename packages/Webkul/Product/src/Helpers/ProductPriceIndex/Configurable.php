<?php

namespace Webkul\Product\Helpers\ProductPriceIndex;

class Configurable extends AbstractPriceIndex
{
    /**
     * Get product minimal price.
     *
     * @return float
     */
    public function getMinimalPrice()
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

            $maxPrices[] = $variantIndexer->getMaximumPrice();
        }

        if (empty($maxPrices)) {
            return 0;
        }

        return min($maxPrices);
    }
}