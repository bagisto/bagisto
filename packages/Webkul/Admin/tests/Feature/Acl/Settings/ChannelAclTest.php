<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to channels with settings.channels permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.channels']);

    get(route('admin.settings.channels.index'))
        ->assertOk();
});

it('should allow creating a channel with settings.channels.create permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.channels', 'settings.channels.create']);

    get(route('admin.settings.channels.create'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to channels without settings.channels permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.channels.index'))
        ->assertUnauthorized();
});

it('should deny channel creation without settings.channels.create permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.channels']);

    get(route('admin.settings.channels.create'))
        ->assertUnauthorized();
});
