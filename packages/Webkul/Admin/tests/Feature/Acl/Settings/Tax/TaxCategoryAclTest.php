<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to tax categories with settings.taxes.tax_categories permission', function () {
    $this->loginAsAdminWithPermissions(['settings', 'settings.taxes', 'settings.taxes.tax_categories']);

    $response = get(route('admin.settings.taxes.categories.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to tax categories without settings.taxes.tax_categories permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.settings.taxes.categories.index'))
        ->assertStatus(401);
});
