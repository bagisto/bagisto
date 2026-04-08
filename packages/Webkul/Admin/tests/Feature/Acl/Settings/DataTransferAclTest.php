<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to data transfer with settings.data_transfer permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.data_transfer', 'settings.data_transfer.imports']);

    $response = get(route('admin.settings.data_transfer.imports.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to data transfer without settings.data_transfer permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.data_transfer.imports.index'))
        ->assertStatus(401);
});
