<?php

namespace Webkul\Omnibus\Contracts;

use Webkul\Product\Contracts\Product;

interface OmnibusPriceProvider
{
    /**
     * Record a price snapshot for a single product across every active channel and currency.
     */
    public function recordPrice(Product $product, ?string $recordedAt = null): int;

    /**
     * Record price snapshots for a batch of products of this provider's type across every active channel and currency.
     *
     * The optional callback fires once per product after its snapshots have been queued, enabling progress reporting.
     */
    public function recordBulkPrice(array $products, ?string $recordedAt = null, ?callable $afterEach = null): int;

    /**
     * Get the lowest price for a product within the configured lookback window prior to any active promo.
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

    /**
     * Get the ids of descendant products whose snapshots must be recorded alongside this one.
     *
     * Returns an empty array for leaf types (simple, virtual, downloadable, booking).
     */
    public function getDescendantProductIds(Product $product): array;
}
