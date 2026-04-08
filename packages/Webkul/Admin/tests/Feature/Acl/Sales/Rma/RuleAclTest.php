<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to rma rules with sales.rma.rules permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.rma', 'sales.rma.rules']);

    $response = get(route('admin.sales.rma.rules.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to rma rules without sales.rma.rules permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.rma.rules.index'))
        ->assertStatus(401);
});
