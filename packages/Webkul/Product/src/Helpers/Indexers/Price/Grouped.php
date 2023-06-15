<?php

namespace Webkul\Product\Helpers\Indexers\Price;

class Grouped extends AbstractType
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
     * @return float
     */
    public function getMinimalPrice($qty = null)
    {
        $minPrices = [];

        foreach ($this->product->grouped_products as $groupOptionProduct) {
            $variant = $groupOptionProduct->associated_product;

            $variantIndexer = $variant->getTypeInstance()
                ->getPriceIndexer()
                ->setCustomerGroup($this->customerGroup)
                ->setProduct($variant);

            $minPrices[] = $variantIndexer->getMinimalPrice();
        }

        return empty($minPrices) ? 0 : min($minPrices);
    }

    /**
     * Get product regular minimal price.
     *
     * @return float
     */
    public function getRegularMinimalPrice()
    {
        $minPrices = [];

        foreach ($this->product->grouped_products as $groupOptionProduct) {
            $minPrices[] = $groupOptionProduct->associated_product->price;
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

            $variantIndexer = $variant->getTypeInstance()
                ->getPriceIndexer()
                ->setCustomerGroup($this->customerGroup)
                ->setProduct($variant);

            $maxPrices[] = $variantIndexer->getMinimalPrice();
        }

        return empty($maxPrices) ? 0 : max($maxPrices);
    }

    /**
     * Get product regular maximum price.
     *
     * @return float
     */
    public function getRegularMaximumPrice()
    {
        $maxPrices = [];

        foreach ($this->product->grouped_products as $groupOptionProduct) {
            $maxPrices[] = $groupOptionProduct->associated_product->price;
        }

        return empty($maxPrices) ? 0 : max($maxPrices);
    }
}