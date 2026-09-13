<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to dashboard with dashboard permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.dashboard.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to dashboard without dashboard permission', function () {
    $this->loginAsAdminWithPermissions(['catalog']);

    get(route('admin.dashboard.index'))
        ->assertUnauthorized();
});
