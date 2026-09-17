<?php

use Webkul\Core\Models\Currency;
use Webkul\Omnibus\Models\OmnibusPrice;
use Webkul\Omnibus\Services\OmnibusPriceManager;

beforeEach(function () {
    $this->manager = app(OmnibusPriceManager::class);

    $this->setOmnibusEnabled(false);

    OmnibusPrice::query()->delete();
});

// ============================================================================
// Enablement Gate
// ============================================================================

it('should record no snapshots when Omnibus is disabled on every channel', function () {
    $product = $this->createSimpleProduct();

    $count = $this->manager->recordPrice($product);

    expect($count)->toBe(0)
        ->and(OmnibusPrice::query()->where('product_id', $product->id)->count())->toBe(0);
});

it('should record a snapshot when Omnibus is enabled on the current channel', function () {
    $product = $this->createSimpleProduct();

    $this->setOmnibusEnabled(true);

    $count = $this->manager->recordPrice($product);

    expect($count)->toBeGreaterThanOrEqual(1)
        ->and(OmnibusPrice::query()->where('product_id', $product->id)->exists())->toBeTrue();
});

// ============================================================================
// Currencies
// ============================================================================

it('should record a snapshot in every currency of the channel', function () {
    $product = $this->createSimpleProduct();

    $currency = Currency::factory()->create();

    core()->getCurrentChannel()->currencies()->attach($currency->id);

    $this->setOmnibusEnabled(true);

    $this->manager->recordPrice($product);

    expect(OmnibusPrice::query()->where('product_id', $product->id)->pluck('currency_code')->all())
        ->toContain(core()->getChannelBaseCurrencyCode(), $currency->code);
});

it('should keep the shopper on the currency they browse in after recording a price', function () {
    $product = $this->createSimpleProduct();

    $currency = Currency::factory()->create();

    core()->getCurrentChannel()->currencies()->attach($currency->id);

    core()->setCurrentCurrency($currency->code);

    $this->setOmnibusEnabled(true);

    $this->manager->recordPrice($product);

    expect(core()->getCurrentCurrencyCode())->toBe($currency->code);
});

// ============================================================================
// Deduplication
// ============================================================================

it('should not duplicate a snapshot when the price is unchanged', function () {
    $product = $this->createSimpleProduct();

    $this->setOmnibusEnabled(true);

    $this->manager->recordPrice($product);
    $this->manager->recordPrice($product);

    expect(OmnibusPrice::query()->where('product_id', $product->id)->count())->toBe(1);
});

// ============================================================================
// Composite Types
// ============================================================================

it('should snapshot every variant of a configurable product', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $this->manager->recordPrice($configurable);

    $variantIds = $configurable->variants->pluck('id')->toArray();

    expect(OmnibusPrice::query()->whereIn('product_id', $variantIds)->count())
        ->toBe(count($variantIds));
});

it('should snapshot the associated products of a grouped product', function () {
    $grouped = $this->createGroupedProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $this->manager->recordPrice($grouped);

    $associatedIds = $grouped->grouped_products->pluck('associated_product_id')->toArray();

    expect(OmnibusPrice::query()->whereIn('product_id', $associatedIds)->count())
        ->toBeGreaterThanOrEqual(count($associatedIds));
});

it('should snapshot a bundle product', function () {
    $bundle = $this->createBundleProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $this->manager->recordPrice($bundle);

    expect(OmnibusPrice::query()->where('product_id', $bundle->id)->exists())->toBeTrue();
});

it('should record each variant exactly once even when walking from the parent', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $this->manager->recordPrice($configurable);

    foreach ($configurable->variants as $variant) {
        expect(OmnibusPrice::query()->where('product_id', $variant->id)->count())->toBe(1);
    }
});

// ============================================================================
// Bulk Path
// ============================================================================

it('should record snapshots for a batch of products in one call', function () {
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
        expect(OmnibusPrice::query()->where('product_id', $product->id)->exists())->toBeTrue();
    }
});

it('should invoke the progress callback once per top-level product only', function () {
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

    expect($advanced)->toBe(2);
});

it('should skip descendants that are already present in the top-level batch', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $this->setOmnibusEnabled(true);
    OmnibusPrice::query()->delete();

    $batch = collect([$configurable])->merge($configurable->variants);

    $this->manager->recordBulkPrice($batch);

    foreach ($configurable->variants as $variant) {
        expect(OmnibusPrice::query()->where('product_id', $variant->id)->count())->toBe(1);
    }
});
