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
    $this->setOmnibusEnabled(true);

    expect($this->manager->isEnabled())->toBeTrue();
});

it('respects an explicit db row that disables Omnibus even when the field default would enable it', function () {
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
