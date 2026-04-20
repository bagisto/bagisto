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
     * Delegate price snapshotting to the product's type-specific provider.
     */
    public function recordPrice(Product $product, ?string $recordedAt = null): int
    {
        return $this->providerResolver->resolve($product)->recordPrice($product, $recordedAt);
    }

    /**
     * Delete snapshot records older than 35 days.
     */
    public function cleanOldRecords(): void
    {
        $this->omnibusPriceRepository->getModel()
            ->where('recorded_at', '<', now()->subDays(35))
            ->delete();
    }
}
