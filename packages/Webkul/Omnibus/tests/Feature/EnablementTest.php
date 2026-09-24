<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Webkul\Omnibus\Models\OmnibusPrice;
use Webkul\Omnibus\Services\OmnibusPriceManager;

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

it('should wire the admin field default to the omnibus.enabled config key', function () {
    $field = system_config()->getConfigField('catalog.products.omnibus.is_enabled');

    expect($field)->not->toBeNull()
        ->and($field['default'])->toBe((int) config('omnibus.enabled'));
});

// ============================================================================
// Resolution Order
// ============================================================================

it('should fall back to the admin field default when no core_config row exists', function () {
    $fieldDefault = (bool) (system_config()->getConfigField('catalog.products.omnibus.is_enabled')['default'] ?? false);

    expect($this->manager->isEnabled())->toBe($fieldDefault);
});

it('should prioritize the core_config db row over the field default', function () {
    $this->setOmnibusEnabled(true);

    expect($this->manager->isEnabled())->toBeTrue();
});

it('should respect an explicit db row that disables Omnibus even when the field default would enable it', function () {
    $this->setOmnibusEnabled(false);

    expect($this->manager->isEnabled())->toBeFalse();
});
