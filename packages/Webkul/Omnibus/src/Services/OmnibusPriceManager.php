<?php

namespace Webkul\Omnibus\Services;

use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Product\Contracts\Product;

class OmnibusPriceManager
{
    /**
     * Create a new service instance.
     */
    public function __construct(
        protected OmnibusPriceRepository $omnibusPriceRepository,
        protected OmnibusPriceProviderResolver $providerResolver
    ) {}

    /**
     * Record a price snapshot via the product's type-specific provider.
     */
    public function recordPrice(Product $product, ?string $recordedAt = null): int
    {
        return $this->providerResolver->resolve($product)->recordPrice($product, $recordedAt);
    }

    /**
     * Get the lowest price for a product within the configured lookback window prior to any active promo.
     */
    public function getLowestPrice(Product $product): ?float
    {
        return $this->providerResolver->resolve($product)->getLowestPrice($product);
    }

    /**
     * Get the lowest price formatted for display.
     */
    public function getLowestPriceFormatted(Product $product): ?string
    {
        return $this->providerResolver->resolve($product)->getLowestPriceFormatted($product);
    }

    /**
     * Render the Omnibus price block for a product.
     */
    public function getOmnibusPriceHtml(Product $product): string
    {
        return $this->providerResolver->resolve($product)->getOmnibusPriceHtml($product);
    }

    /**
     * Delete snapshot records older than the configured retention window.
     */
    public function cleanOldRecords(): void
    {
        $this->omnibusPriceRepository->getModel()
            ->where('recorded_at', '<', now()->subDays(config('omnibus.snapshots.retention_days')))
            ->delete();
    }
}
