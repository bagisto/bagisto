<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to themes with appearance.themes permission', function () {
    $this->loginAsAdminWithPermissions(['appearance', 'appearance.themes']);

    $response = get(route('admin.appearance.themes.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to themes without appearance.themes permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.appearance.themes.index'))
        ->assertStatus(401);
});
