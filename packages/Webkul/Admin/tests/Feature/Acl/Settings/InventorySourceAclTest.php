<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to inventory sources with settings.inventory_sources permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.inventory_sources']);

    get(route('admin.settings.inventory_sources.index'))
        ->assertOk();
});

it('should allow creating an inventory source with settings.inventory_sources.create permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.inventory_sources', 'settings.inventory_sources.create']);

    get(route('admin.settings.inventory_sources.create'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to inventory sources without settings.inventory_sources permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.inventory_sources.index'))
        ->assertUnauthorized();
});

it('should deny inventory source creation without settings.inventory_sources.create permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.inventory_sources']);

    get(route('admin.settings.inventory_sources.create'))
        ->assertUnauthorized();
});
