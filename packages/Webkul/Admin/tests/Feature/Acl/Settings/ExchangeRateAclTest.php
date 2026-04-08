<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to exchange rates with settings.exchange_rates permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.exchange_rates']);

    $response = get(route('admin.settings.exchange_rates.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to exchange rates without settings.exchange_rates permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.exchange_rates.index'))
        ->assertStatus(401);
});
