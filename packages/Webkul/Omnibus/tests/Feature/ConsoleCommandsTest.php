<?php

use Illuminate\Support\Facades\Artisan;
use Webkul\Omnibus\Models\OmnibusPrice;

// ============================================================================
// Setup
// ============================================================================

beforeEach(function () {
    // Tests start with Omnibus disabled (no core_config row) so the listener
    // does not auto-snapshot during createSimpleProduct.
    $this->setOmnibusEnabled(false);

    OmnibusPrice::query()->delete();
});

// ============================================================================
// omnibus:snapshot-prices
// ============================================================================

it('captures snapshots when Omnibus is enabled', function () {
    $this->createSimpleProduct();

    $this->setOmnibusEnabled(true);

    Artisan::call('omnibus:snapshot-prices');

    expect(Artisan::output())->toContain('Captured');
    expect(OmnibusPrice::count())->toBeGreaterThan(0);
});

it('warns and exits when Omnibus is disabled on every configured channel', function () {
    $this->createSimpleProduct();

    Artisan::call('omnibus:snapshot-prices');

    expect(Artisan::output())->toContain(trans('omnibus::app.console.disabled-all-channels'));
    expect(OmnibusPrice::count())->toBe(0);
});

// ============================================================================
// omnibus:purge-old-snapshots (default)
// ============================================================================

it('purges snapshots older than the retention window and keeps recent ones', function () {
    $product = $this->createSimpleProduct();
    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    OmnibusPrice::create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 50.00,
        'recorded_at' => now()->subDays(40),
    ]);

    OmnibusPrice::create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 100.00,
        'recorded_at' => now()->subDays(5),
    ]);

    Artisan::call('omnibus:purge-old-snapshots');

    expect(OmnibusPrice::count())->toBe(1);
    expect(OmnibusPrice::first()->price)->toEqual(100.00);
    expect(Artisan::output())->toContain(trans('omnibus::app.console.old-purged'));
});

// ============================================================================
// omnibus:purge-old-snapshots --all
// ============================================================================

it('deletes every snapshot when --all and --force are passed', function () {
    $product = $this->createSimpleProduct();
    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    OmnibusPrice::create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 100.00,
        'recorded_at' => now()->subDays(5),
    ]);

    OmnibusPrice::create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 50.00,
        'recorded_at' => now()->subDays(1),
    ]);

    Artisan::call('omnibus:purge-old-snapshots', ['--all' => true, '--force' => true]);

    expect(OmnibusPrice::count())->toBe(0);
    expect(Artisan::output())->toContain(trans('omnibus::app.console.all-deleted'));
});

it('aborts --all without --force when running non-interactively', function () {
    $product = $this->createSimpleProduct();
    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    OmnibusPrice::create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 100.00,
        'recorded_at' => now()->subDays(1),
    ]);

    // Non-interactive run: confirm() returns the default (false) → aborts.
    Artisan::call('omnibus:purge-old-snapshots', ['--all' => true]);

    expect(OmnibusPrice::count())->toBe(1);
    expect(Artisan::output())->toContain(trans('omnibus::app.console.aborted'));
});
