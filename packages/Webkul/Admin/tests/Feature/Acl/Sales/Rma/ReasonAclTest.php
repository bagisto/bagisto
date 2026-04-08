<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to rma reasons with sales.rma.reasons permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.rma', 'sales.rma.reasons']);

    $response = get(route('admin.sales.rma.reasons.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to rma reasons without sales.rma.reasons permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.rma.reasons.index'))
        ->assertStatus(401);
});
