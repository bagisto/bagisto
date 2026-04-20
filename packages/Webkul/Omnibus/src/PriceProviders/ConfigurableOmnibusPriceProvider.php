<?php

namespace Webkul\Omnibus\PriceProviders;

use Webkul\Product\Contracts\Product;

class ConfigurableOmnibusPriceProvider extends DefaultOmnibusPriceProvider
{
    /**
     * Get the ids of the configurable product's variants.
     */
    public function getDescendantProductIds(Product $product): array
    {
        return $product->variants()->pluck('id')->all();
    }

    /**
     * Render the Omnibus price block with variant-aware client-side switching.
     */
    public function getOmnibusPriceHtml(Product $product): string
    {
        if (! $product->getTypeInstance()->haveDiscount()) {
            return '';
        }

        $lowestPrice = $this->getLowestPrice($product);

        // No snapshot history yet — don't claim a "lowest price" we cannot
        // substantiate. Configurable parents have no direct price, so falling
        // back to $product->price would render "0.00".
        if (
            is_null($lowestPrice)
            || $lowestPrice <= 0
        ) {
            return '';
        }

        $formattedPrice = core()->formatPrice($lowestPrice, core()->getCurrentCurrencyCode());

        $variantPrices = [];

        foreach ($product->variants as $variant) {
            $formattedVariantPrice = $this->getLowestPriceFormatted($variant);

            if ($formattedVariantPrice) {
                $variantPrices[$variant->id] = $formattedVariantPrice;
            }
        }

        return view('omnibus::shop.price-info.configurable', compact('formattedPrice', 'variantPrices'))->render();
    }
}
