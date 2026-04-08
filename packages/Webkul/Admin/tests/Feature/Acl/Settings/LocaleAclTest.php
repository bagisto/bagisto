<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to locales with settings.locales permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.locales']);

    $response = get(route('admin.settings.locales.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to locales without settings.locales permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.locales.index'))
        ->assertStatus(401);
});
