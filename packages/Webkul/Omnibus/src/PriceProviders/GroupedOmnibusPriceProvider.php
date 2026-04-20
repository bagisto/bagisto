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

    /**
     * Render the Omnibus price block as a per-associated-product list.
     *
     * A grouped product has no single price of its own — customers buy each
     * associated item à la carte — so showing one aggregate "lowest price"
     * at the parent level is misleading. This renders a short list of each
     * discounted associated product with its historical low.
     */
    public function getOmnibusPriceHtml(Product $product): string
    {
        if (! $product->getTypeInstance()->haveDiscount()) {
            return '';
        }

        $items = [];

        foreach ($product->grouped_products as $groupedProduct) {
            $associated = $groupedProduct->associated_product;

            if (
                ! $associated
                || ! $associated->getTypeInstance()->haveDiscount()
            ) {
                continue;
            }

            $formattedPrice = $this->getLowestPriceFormatted($associated);

            if (! $formattedPrice) {
                continue;
            }

            $items[] = [
                'name' => $associated->name,
                'price' => $formattedPrice,
            ];
        }

        if (empty($items)) {
            return '';
        }

        return view('shop::products.omnibus.grouped', compact('items'))->render();
    }
}
