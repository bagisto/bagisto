<?php

use function Pest\Laravel\get;

beforeEach(function () {
    $this->loginAsCustomer();
});

// ============================================================================
// Feature Disabled
// ============================================================================

it('should not expose a gdpr page while the feature is disabled', function (string $route) {
    $this->setConfig('general.gdpr.settings.enabled', false);

    get(route($route))->assertNotFound();
})->with([
    'data request' => 'shop.customers.account.gdpr.index',
    'cookie consent' => 'shop.customers.gdpr.cookie_consent',
]);

// ============================================================================
// Feature Enabled
// ============================================================================

it('should open a gdpr page while the feature is enabled', function (string $route) {
    $this->setConfig('general.gdpr.settings.enabled', true);

    get(route($route))->assertOk();
})->with([
    'data request' => 'shop.customers.account.gdpr.index',
    'cookie consent' => 'shop.customers.gdpr.cookie_consent',
]);
