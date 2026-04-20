<?php

namespace Webkul\Omnibus\PriceProviders;

use Webkul\Product\Contracts\Product;

class ConfigurableOmnibusPriceProvider extends DefaultOmnibusPriceProvider
{
    /**
     * Render the Omnibus price block with variant-aware client-side switching.
     */
    public function getOmnibusPriceHtml(Product $product): string
    {
        if (! core()->getConfigData('catalog.products.omnibus.is_enabled')) {
            return '';
        }

        if (! $product->getTypeInstance()->haveDiscount()) {
            return '';
        }

        $lowestPrice = $this->getLowestPrice($product) ?? $product->price;
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
