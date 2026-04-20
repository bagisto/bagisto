<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Webkul\Omnibus\Models\OmnibusPrice;
use Webkul\Omnibus\Services\OmnibusPriceManager;

// ============================================================================
// Setup
// ============================================================================

beforeEach(function () {
    $this->manager = app(OmnibusPriceManager::class);

    // Start each test with no persisted row so the fallback path is exercised.
    DB::table('core_config')
        ->where('code', 'catalog.products.omnibus.is_enabled')
        ->delete();

    Cache::flush();

    OmnibusPrice::query()->delete();
});

// ============================================================================
// Wiring
// ============================================================================

it('wires the admin field default to the omnibus.enabled config key', function () {
    $field = system_config()->getConfigField('catalog.products.omnibus.is_enabled');

    expect($field)->not->toBeNull();

    // The admin field's default must reflect whatever config('omnibus.enabled')
    // resolved to at provider-registration time. If someone breaks the wiring
    // (e.g., drops the config() reference), this test fails immediately.
    expect($field['default'])->toBe(config('omnibus.enabled'));
});

// ============================================================================
// Resolution Order
// ============================================================================

it('falls back to the admin field default when no core_config row exists', function () {
    $fieldDefault = (bool) (system_config()->getConfigField('catalog.products.omnibus.is_enabled')['default'] ?? false);

    expect($this->manager->isEnabled())->toBe($fieldDefault);
});

it('prioritizes the core_config db row over the field default', function () {
    // Enable via the admin-level mechanism (DB row), regardless of the fallback.
    $this->setOmnibusEnabled(true);

    expect($this->manager->isEnabled())->toBeTrue();
});

it('respects an explicit db row that disables Omnibus even when the field default would enable it', function () {
    // Simulate the "admin turned it off on this channel" scenario.
    $channelCode = core()->getCurrentChannel()->code;

    DB::table('core_config')
        ->where('code', 'catalog.products.omnibus.is_enabled')
        ->where('channel_code', $channelCode)
        ->delete();

    DB::table('core_config')->insert([
        'code' => 'catalog.products.omnibus.is_enabled',
        'value' => '0',
        'channel_code' => $channelCode,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    Cache::flush();

    expect($this->manager->isEnabled())->toBeFalse();
});
