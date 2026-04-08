<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to configuration with configuration permission', function () {
    $this->loginAsAdminWithPermissions(['configuration']);

    $response = get(route('admin.configuration.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to configuration without configuration permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.configuration.index'))
        ->assertStatus(401);
});
