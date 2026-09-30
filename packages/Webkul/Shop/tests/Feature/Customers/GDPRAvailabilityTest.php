<?php

use Illuminate\Support\Facades\Cache;
use Webkul\Core\Models\CoreConfig;

use function Pest\Laravel\get;

/**
 * Turn the storefront's GDPR feature on or off, past the repository's cache.
 */
function setGDPREnabled(bool $enabled): void
{
    CoreConfig::updateOrCreate(
        [
            'code' => 'general.gdpr.settings.enabled',
            'channel_code' => core()->getRequestedChannelCode(),
            'locale_code' => core()->getRequestedLocaleCode(),
        ],
        ['value' => $enabled ? 1 : 0],
    );

    Cache::flush();
}

beforeEach(function () {
    $this->loginAsCustomer();
});

// ============================================================================
// Feature Disabled
// ============================================================================

it('should not expose a gdpr page while the feature is disabled', function (string $route) {
    setGDPREnabled(false);

    get(route($route))->assertNotFound();
})->with([
    'data request' => 'shop.customers.account.gdpr.index',
    'cookie consent' => 'shop.customers.gdpr.cookie-consent',
]);

// ============================================================================
// Feature Enabled
// ============================================================================

it('should open a gdpr page while the feature is enabled', function (string $route) {
    setGDPREnabled(true);

    get(route($route))->assertOk();
})->with([
    'data request' => 'shop.customers.account.gdpr.index',
    'cookie consent' => 'shop.customers.gdpr.cookie-consent',
]);
