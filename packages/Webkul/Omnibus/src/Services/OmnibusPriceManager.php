<?php

namespace Webkul\Omnibus\Services;

use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Product\Contracts\Product;
use Webkul\Product\Repositories\ProductRepository;

class OmnibusPriceManager
{
    /**
     * Create a new service instance.
     */
    public function __construct(
        protected OmnibusPriceRepository $omnibusPriceRepository,
        protected OmnibusPriceProviderResolver $providerResolver,
        protected ProductRepository $productRepository
    ) {}

    /**
     * Record a price snapshot for a single product via its type-specific provider.
     */
    public function recordPrice(Product $product, ?string $recordedAt = null): int
    {
        return $this->recordBulkPrice([$product], $recordedAt);
    }

    /**
     * Record price snapshots for many products, batching per type-specific provider and walking into new descendants.
     *
     * The optional callback fires once per top-level product for progress reporting; descendants do not trigger it.
     */
    public function recordBulkPrice(iterable $products, ?string $recordedAt = null, ?callable $afterEachProduct = null): int
    {
        $products = is_array($products) ? $products : iterator_to_array($products);

        if (empty($products)) {
            return 0;
        }

        $snapshotCount = 0;
        $seenIds = [];

        foreach ($products as $product) {
            $seenIds[$product->id] = true;
        }

        $currentBatch = $products;
        $firstLevel = true;

        while (! empty($currentBatch)) {
            $snapshotCount += $this->dispatchToProviders(
                $currentBatch,
                $recordedAt,
                $firstLevel ? $afterEachProduct : null
            );

            $currentBatch = $this->collectDescendants($currentBatch, $seenIds);

            foreach ($currentBatch as $descendant) {
                $seenIds[$descendant->id] = true;
            }

            $firstLevel = false;
        }

        return $snapshotCount;
    }

    /**
     * Get the lowest price for a product within the configured lookback window prior to any active promo.
     */
    public function getLowestPrice(Product $product): ?float
    {
        if (! $this->isEnabled()) {
            return null;
        }

        return $this->providerResolver->resolve($product)->getLowestPrice($product);
    }

    /**
     * Get the lowest price formatted for display.
     */
    public function getLowestPriceFormatted(Product $product): ?string
    {
        if (! $this->isEnabled()) {
            return null;
        }

        return $this->providerResolver->resolve($product)->getLowestPriceFormatted($product);
    }

    /**
     * Render the Omnibus price block for a product.
     */
    public function getOmnibusPriceHtml(Product $product): string
    {
        if (! $this->isEnabled()) {
            return '';
        }

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

    /**
     * Delete every snapshot record regardless of age.
     */
    public function cleanAllRecords(): void
    {
        $this->omnibusPriceRepository->getModel()->newQuery()->delete();
    }

    /**
     * Whether Omnibus is enabled on the current channel.
     *
     * Resolution order handled natively by core()->getConfigData():
     * core_config DB row → field's default (env OMNIBUS_ENABLED) → null.
     */
    public function isEnabled(): bool
    {
        return (bool) core()->getConfigData('catalog.products.omnibus.is_enabled');
    }

    /**
     * Group the batch by product type and dispatch each same-type sub-batch to its provider.
     */
    protected function dispatchToProviders(array $products, ?string $recordedAt, ?callable $afterEach): int
    {
        $byType = [];

        foreach ($products as $product) {
            $byType[$product->type][] = $product;
        }

        $snapshotCount = 0;

        foreach ($byType as $typeBatch) {
            $provider = $this->providerResolver->resolve($typeBatch[0]);

            $snapshotCount += $provider->recordBulkPrice($typeBatch, $recordedAt, $afterEach);
        }

        return $snapshotCount;
    }

    /**
     * Hydrate descendant Products for the given batch, skipping any already snapshotted in this run.
     *
     * Each product's descendants come from its type-specific provider — Omnibus
     * does not rely on Bagisto's generic getTypeInstance()->getChildrenIds()
     * because that method returns incorrect data for bundle products.
     */
    protected function collectDescendants(array $products, array $seenIds): array
    {
        $childrenIds = [];

        foreach ($products as $product) {
            $provider = $this->providerResolver->resolve($product);

            foreach ($provider->getDescendantProductIds($product) as $id) {
                if (isset($seenIds[$id])) {
                    continue;
                }

                $childrenIds[] = $id;
            }
        }

        if (empty($childrenIds)) {
            return [];
        }

        return $this->productRepository
            ->findWhereIn('id', array_values(array_unique($childrenIds)))
            ->all();
    }
}
