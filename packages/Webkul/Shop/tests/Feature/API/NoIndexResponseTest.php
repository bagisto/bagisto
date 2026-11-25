<?php

use function Pest\Laravel\get;
use function Pest\Laravel\getJson;

// ============================================================================
// Shop API
// ============================================================================

it('should ask search engines not to index a shop api response', function () {
    getJson(route('shop.api.core.countries'))
        ->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex');
});

it('should ask search engines not to index a refused shop api response', function () {
    getJson(route('shop.api.customers.account.addresses.index'))
        ->assertUnauthorized()
        ->assertHeader('X-Robots-Tag', 'noindex');
});

// ============================================================================
// Storefront Pages
// ============================================================================

it('should leave the storefront pages indexable', function () {
    get(route('shop.home.index'))
        ->assertOk()
        ->assertHeaderMissing('X-Robots-Tag');
});
