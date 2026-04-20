<?php

namespace Webkul\Omnibus\Contracts;

use Webkul\Product\Contracts\Product;

interface OmnibusPriceProvider
{
    /**
     * Record a price snapshot for the product across every active channel and currency.
     */
    public function recordPrice(Product $product, ?string $recordedAt = null): int;

    /**
     * Get the lowest price for a product in the 30 days prior to any active promo.
     */
    public function getLowestPrice(Product $product): ?float;

    /**
     * Get the lowest price formatted for display.
     */
    public function getLowestPriceFormatted(Product $product): ?string;

    /**
     * Render the Omnibus price block for a product.
     */
    public function getOmnibusPriceHtml(Product $product): string;
}
