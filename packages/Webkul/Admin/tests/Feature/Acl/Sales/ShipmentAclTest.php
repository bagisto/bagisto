<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to shipments index with sales.shipments permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.shipments']);

    $response = get(route('admin.sales.shipments.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to shipments index without sales.shipments permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.shipments.index'))
        ->assertStatus(401);
});
