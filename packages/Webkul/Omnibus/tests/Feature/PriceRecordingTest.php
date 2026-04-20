<?php

use Webkul\Omnibus\Models\OmnibusPrice;
use Webkul\Omnibus\Services\OmnibusPriceManager;

// ============================================================================
// Setup
// ============================================================================

beforeEach(function () {
    $this->manager = app(OmnibusPriceManager::class);

    // Tests start with Omnibus disabled (no core_config row). Individual
    // tests enable the feature explicitly before invoking the manager so
    // the listener does not auto-snapshot during createSimpleProduct.
    $this->setOmnibusEnabled(false);

    OmnibusPrice::query()->delete();
});

// ============================================================================
// Enablement Gate
// ============================================================================

it('records no snapshots when Omnibus is disabled on every channel', function () {
    $product = $this->createSimpleProduct();

    $count = $this->manager->recordPrice($product);

    expect($count)->toBe(0);
    expect(OmnibusPrice::where('product_id', $product->id)->count())->toBe(0);
});

it('records a snapshot when Omnibus is enabled on the current channel', function () {
    $product = $this->createSimpleProduct();

    $this->setOmnibusEnabled(true);

    $count = $this->manager->recordPrice($product);

    expect($count)->toBeGreaterThanOrEqual(1);
    expect(OmnibusPrice::where('product_id', $product->id)->exists())->toBeTrue();
});

// ============================================================================
// Deduplication
// ============================================================================

it('does not duplicate a snapshot when the price is unchanged', function () {
    $product = $this->createSimpleProduct();

    $this->setOmnibusEnabled(true);

    $this->manager->recordPrice($product);
    $this->manager->recordPrice($product);

    expect(OmnibusPrice::where('product_id', $product->id)->count())->toBe(1);
});

// ============================================================================
// Composite Types
// ============================================================================

it('snapshots every variant of a configurable product', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $this->manager->recordPrice($configurable);

    $variantIds = $configurable->variants->pluck('id')->toArray();

    expect(OmnibusPrice::whereIn('product_id', $variantIds)->count())
        ->toBe(count($variantIds));
});

it('snapshots the associated products of a grouped product', function () {
    $grouped = $this->createGroupedProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $this->manager->recordPrice($grouped);

    $associatedIds = $grouped->grouped_products->pluck('associated_product_id')->toArray();

    expect(OmnibusPrice::whereIn('product_id', $associatedIds)->count())
        ->toBeGreaterThanOrEqual(count($associatedIds));
});

it('snapshots a bundle product', function () {
    $bundle = $this->createBundleProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $this->manager->recordPrice($bundle);

    expect(OmnibusPrice::where('product_id', $bundle->id)->exists())->toBeTrue();
});

it('records each variant exactly once even when walking from the parent', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $this->manager->recordPrice($configurable);

    foreach ($configurable->variants as $variant) {
        expect(OmnibusPrice::where('product_id', $variant->id)->count())->toBe(1);
    }
});

// ============================================================================
// Bulk Path
// ============================================================================

it('records snapshots for a batch of products in one call', function () {
    $products = collect([
        $this->createSimpleProduct(['price' => ['float_value' => 100]]),
        $this->createSimpleProduct(['price' => ['float_value' => 200]]),
        $this->createSimpleProduct(['price' => ['float_value' => 300]]),
    ]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $count = $this->manager->recordBulkPrice($products);

    expect($count)->toBeGreaterThanOrEqual(3);

    foreach ($products as $product) {
        expect(OmnibusPrice::where('product_id', $product->id)->exists())->toBeTrue();
    }
});

it('invokes the progress callback once per top-level product only', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);
    $simple = $this->createSimpleProduct();

    $this->setOmnibusEnabled(true);

    $advanced = 0;

    $this->manager->recordBulkPrice(
        [$configurable, $simple],
        null,
        function () use (&$advanced) {
            $advanced++;
        }
    );

    // Callback fires for the two top-level products; variants are descendants
    // and must not trigger additional advances.
    expect($advanced)->toBe(2);
});

it('skips descendants that are already present in the top-level batch', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $batch = collect([$configurable])->merge($configurable->variants);

    $this->manager->recordBulkPrice($batch);

    // Each variant has exactly one snapshot despite being reachable via its
    // parent's getChildrenIds() AND present as a top-level entry in the batch.
    foreach ($configurable->variants as $variant) {
        expect(OmnibusPrice::where('product_id', $variant->id)->count())->toBe(1);
    }
});
