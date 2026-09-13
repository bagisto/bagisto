<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to data transfer with settings.data_transfer permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.data_transfer', 'settings.data_transfer.imports']);

    get(route('admin.settings.data_transfer.imports.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to data transfer without settings.data_transfer permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.data_transfer.imports.index'))
        ->assertUnauthorized();
});
