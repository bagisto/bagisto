<?php

use Illuminate\Support\Facades\Artisan;
use Webkul\Omnibus\Models\OmnibusPrice;

beforeEach(function () {
    $this->setOmnibusEnabled(false);

    OmnibusPrice::query()->delete();
});

// ============================================================================
// Snapshot Prices Command
// ============================================================================

it('should capture snapshots when Omnibus is enabled', function () {
    $this->createSimpleProduct();

    $this->setOmnibusEnabled(true);

    Artisan::call('omnibus:snapshot-prices');

    expect(Artisan::output())->toContain('Captured')
        ->and(OmnibusPrice::query()->count())->toBeGreaterThan(0);
});

it('should warn and exit when Omnibus is disabled on every configured channel', function () {
    $this->createSimpleProduct();

    Artisan::call('omnibus:snapshot-prices');

    expect(Artisan::output())->toContain(trans('omnibus::app.console.disabled-all-channels'))
        ->and(OmnibusPrice::query()->count())->toBe(0);
});

// ============================================================================
// Purge Old Snapshots Command
// ============================================================================

it('should purge snapshots older than the retention window and keep recent ones', function () {
    $product = $this->createSimpleProduct();
    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    OmnibusPrice::query()->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 50.00,
        'recorded_at' => now()->subDays(40),
    ]);

    OmnibusPrice::query()->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 100.00,
        'recorded_at' => now()->subDays(5),
    ]);

    Artisan::call('omnibus:purge-old-snapshots');

    expect(OmnibusPrice::query()->count())->toBe(1)
        ->and(OmnibusPrice::query()->first()->price)->toEqual(100.00)
        ->and(Artisan::output())->toContain(trans('omnibus::app.console.old-purged'));
});

// ============================================================================
// Purge Every Snapshot Command
// ============================================================================

it('should delete every snapshot when --all and --force are passed', function () {
    $product = $this->createSimpleProduct();
    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    OmnibusPrice::query()->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 100.00,
        'recorded_at' => now()->subDays(5),
    ]);

    OmnibusPrice::query()->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 50.00,
        'recorded_at' => now()->subDays(1),
    ]);

    Artisan::call('omnibus:purge-old-snapshots', ['--all' => true, '--force' => true]);

    expect(OmnibusPrice::query()->count())->toBe(0)
        ->and(Artisan::output())->toContain(trans('omnibus::app.console.all-deleted'));
});

it('should abort --all without --force when running non-interactively', function () {
    $product = $this->createSimpleProduct();
    $channelId = core()->getCurrentChannel()->id;
    $currencyCode = core()->getCurrentCurrencyCode();

    OmnibusPrice::query()->create([
        'product_id' => $product->id,
        'channel_id' => $channelId,
        'currency_code' => $currencyCode,
        'price' => 100.00,
        'recorded_at' => now()->subDays(1),
    ]);

    Artisan::call('omnibus:purge-old-snapshots', ['--all' => true]);

    expect(OmnibusPrice::query()->count())->toBe(1)
        ->and(Artisan::output())->toContain(trans('omnibus::app.console.aborted'));
});
