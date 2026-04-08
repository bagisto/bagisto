<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to orders index with sales.orders permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.orders']);

    $response = get(route('admin.sales.orders.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to orders index without sales.orders permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.orders.index'))
        ->assertStatus(401);
});
