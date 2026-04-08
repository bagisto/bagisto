<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to channels with settings.channels permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.channels']);

    $response = get(route('admin.settings.channels.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow creating a channel with settings.channels.create permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.channels', 'settings.channels.create']);

    $response = get(route('admin.settings.channels.create'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to channels without settings.channels permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.channels.index'))
        ->assertStatus(401);
});

it('should deny channel creation without settings.channels.create permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.channels']);

    get(route('admin.settings.channels.create'))
        ->assertStatus(401);
});
