<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to currencies with settings.currencies permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.currencies']);

    $response = get(route('admin.settings.currencies.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to currencies without settings.currencies permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.currencies.index'))
        ->assertStatus(401);
});
