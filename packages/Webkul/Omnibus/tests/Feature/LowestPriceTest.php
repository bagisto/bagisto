<?php

use Carbon\Carbon;
use Webkul\Omnibus\Models\OmnibusPrice;
use Webkul\Omnibus\Repositories\OmnibusPriceRepository;
use Webkul\Omnibus\Services\OmnibusPriceManager;

// ============================================================================
// Setup
// ============================================================================

beforeEach(function () {
    $this->manager = app(OmnibusPriceManager::class);
    $this->repository = app(OmnibusPriceRepository::class);

    $this->setOmnibusEnabled(true);

    OmnibusPrice::query()->delete();

    $this->now = Carbon::parse('2026-04-16 12:00:00');
    Carbon::setTestNow($this->now);
});

afterEach(function () {
    Carbon::setTestNow();
});

// ============================================================================
// getLowestPrice
// ============================================================================

it('returns the lowest snapshot price within the lookback window', function () {
    $product = $this->createSimpleProduct();

    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 100.00,
        'recorded_at' => $this->now->copy()->subDays(10),
    ]);

    $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 150.00,
        'recorded_at' => $this->now->copy()->subDays(5),
    ]);

    expect($this->manager->getLowestPrice($product))->toBe(100.0);
});

it('ignores snapshots older than the configured lookback window', function () {
    $product = $this->createSimpleProduct();

    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 50.00,
        'recorded_at' => $this->now->copy()->subDays(40),
    ]);

    $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 100.00,
        'recorded_at' => $this->now->copy()->subDays(10),
    ]);

    expect($this->manager->getLowestPrice($product))->toBe(100.0);
});

it('aggregates the lowest price across configurable variants', function () {
    $configurable = $this->createConfigurableProduct([100, 200]);

    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    foreach ($configurable->variants as $index => $variant) {
        $this->repository->create([
            'product_id' => $variant->id,
            'channel_id' => $channelId,
            'currency_code' => $currencyCode,
            'price' => $index === 0 ? 45.00 : 90.00,
            'recorded_at' => $this->now->copy()->subDays(10),
        ]);
    }

    expect($this->manager->getLowestPrice($configurable))->toBe(45.0);
});

it('returns null when Omnibus is disabled', function () {
    $product = $this->createSimpleProduct();

    $this->setOmnibusEnabled(false);

    expect($this->manager->getLowestPrice($product))->toBeNull();
});

// ============================================================================
// getLowestPriceFormatted
// ============================================================================

it('formats the lowest price as a currency string', function () {
    $product = $this->createSimpleProduct();

    $this->repository->create([
        'product_id' => $product->id,
        'channel_id' => core()->getCurrentChannel()->id,
        'currency_code' => core()->getCurrentCurrencyCode(),
        'price' => 99.00,
        'recorded_at' => $this->now->copy()->subDays(10),
    ]);

    $formatted = $this->manager->getLowestPriceFormatted($product);

    expect($formatted)->toBeString();
    expect($formatted)->toContain('99');
});

it('returns null formatted price when Omnibus is disabled', function () {
    $product = $this->createSimpleProduct();

    $this->setOmnibusEnabled(false);

    expect($this->manager->getLowestPriceFormatted($product))->toBeNull();
});

// ============================================================================
// getOmnibusPriceHtml
// ============================================================================

it('returns an empty string when Omnibus is disabled', function () {
    $product = $this->createSimpleProduct();

    $this->setOmnibusEnabled(false);

    expect($this->manager->getOmnibusPriceHtml($product))->toBe('');
});

it('returns an empty string when the product has no active discount', function () {
    // A freshly created simple product has no special_price, so haveDiscount() is false.
    $product = $this->createSimpleProduct();

    expect($this->manager->getOmnibusPriceHtml($product))->toBe('');
});

it('returns an empty string for a grouped product with no discounted associated items', function () {
    $grouped = $this->createGroupedProduct([100, 200]);

    // No associated product has a special_price, so haveDiscount() cascades false.
    expect($this->manager->getOmnibusPriceHtml($grouped))->toBe('');
});
