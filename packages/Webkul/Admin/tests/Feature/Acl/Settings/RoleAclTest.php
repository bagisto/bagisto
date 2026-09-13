<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to roles with settings.roles permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.roles']);

    get(route('admin.settings.roles.index'))
        ->assertOk();
});

it('should allow creating a role with settings.roles.create permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.roles', 'settings.roles.create']);

    get(route('admin.settings.roles.create'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to roles without settings.roles permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.roles.index'))
        ->assertUnauthorized();
});

it('should deny role creation without settings.roles.create permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.roles']);

    get(route('admin.settings.roles.create'))
        ->assertUnauthorized();
});
