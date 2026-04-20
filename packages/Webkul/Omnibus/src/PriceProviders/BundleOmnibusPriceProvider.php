<?php

namespace Webkul\Omnibus\PriceProviders;

use Illuminate\Support\Facades\DB;
use Webkul\Product\Contracts\Product;

class BundleOmnibusPriceProvider extends DefaultOmnibusPriceProvider
{
    /**
     * Get the ids of the products inside this bundle's options.
     *
     * Bypasses Bagisto core's Bundle::getChildrenIds() — that method plucks
     * product_bundle_options.product_id, which is the parent bundle's id, not
     * the option products. The correct chain is:
     *
     *   product_bundle_options.id  →  product_bundle_option_products.product_bundle_option_id
     *   product_bundle_option_products.product_id  →  the actual option product
     */
    public function getDescendantProductIds(Product $product): array
    {
        return DB::table('product_bundle_option_products')
            ->join(
                'product_bundle_options',
                'product_bundle_option_products.product_bundle_option_id',
                '=',
                'product_bundle_options.id'
            )
            ->where('product_bundle_options.product_id', $product->id)
            ->pluck('product_bundle_option_products.product_id')
            ->unique()
            ->values()
            ->all();
    }
}
