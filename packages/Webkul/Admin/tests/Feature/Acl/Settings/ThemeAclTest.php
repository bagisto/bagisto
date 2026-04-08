<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to themes with settings.themes permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.themes']);

    $response = get(route('admin.settings.themes.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to themes without settings.themes permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.themes.index'))
        ->assertStatus(401);
});
