<?php

namespace Webkul\Omnibus\PriceProviders;

use Webkul\Product\Contracts\Product;

class GroupedOmnibusPriceProvider extends DefaultOmnibusPriceProvider
{
    /**
     * Get the ids of the associated simple products attached to this grouped product.
     */
    public function getDescendantProductIds(Product $product): array
    {
        return $product->grouped_products()
            ->pluck('associated_product_id')
            ->unique()
            ->values()
            ->all();
    }
}
