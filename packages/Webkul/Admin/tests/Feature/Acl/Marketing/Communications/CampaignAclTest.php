<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to campaigns with marketing.communications.campaigns permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.communications', 'marketing.communications.campaigns']);

    get(route('admin.marketing.communications.campaigns.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to campaigns without marketing.communications.campaigns permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.communications.campaigns.index'))
        ->assertUnauthorized();
});
