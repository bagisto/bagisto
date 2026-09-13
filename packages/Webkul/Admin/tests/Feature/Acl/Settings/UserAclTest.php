<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to users with settings.users permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.users']);

    get(route('admin.settings.users.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to users without settings.users permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.users.index'))
        ->assertUnauthorized();
});
