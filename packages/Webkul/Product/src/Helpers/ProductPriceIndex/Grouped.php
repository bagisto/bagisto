<?php

namespace Webkul\Product\Helpers\ProductPriceIndex;

class Grouped extends AbstractPriceIndex
{
    /**
     * Get product minimal price.
     *
     * @return float
     */
    public function getMinimalPrice()
    {
        $minPrices = [];

        foreach ($this->product->grouped_products as $groupOptionProduct) {
            $variant = $groupOptionProduct->associated_product;

            $variantIndexer = app($variant->getTypeInstance()->getPriceIndexer())
                ->setCustomerGroup($this->customerGroup)
                ->setProduct($variant);

            $minPrices[] = $variantIndexer->getMinimalPrice();
        }

        return empty($minPrices) ? 0 : min($minPrices);
    }

    /**
     * Get product maximum price.
     *
     * @return float
     */
    public function getMaximumPrice()
    {
        $maxPrices = [];

        foreach ($this->product->grouped_products as $groupOptionProduct) {
            $variant = $groupOptionProduct->associated_product;

            $variantIndexer = app($variant->getTypeInstance()->getPriceIndexer())
                ->setCustomerGroup($this->customerGroup)
                ->setProduct($variant);

            $maxPrices[] = $variantIndexer->getMaximumPrice();
        }

        return empty($maxPrices) ? 0 : max($maxPrices);
    }
}