<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to tax rates with settings.taxes.tax_rates permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.taxes', 'settings.taxes.tax_rates']);

    $response = get(route('admin.settings.taxes.rates.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to tax rates without settings.taxes.tax_rates permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.taxes.rates.index'))
        ->assertStatus(401);
});
