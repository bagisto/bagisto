<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to subscribers with marketing.communications.subscribers permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.communications', 'marketing.communications.subscribers']);

    get(route('admin.marketing.communications.subscribers.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to subscribers without marketing.communications.subscribers permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.communications.subscribers.index'))
        ->assertUnauthorized();
});
