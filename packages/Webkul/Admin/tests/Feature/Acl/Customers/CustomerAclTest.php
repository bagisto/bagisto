<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to customers index with customers.customers permission', function () {
    $this->loginAsAdminWithPermissions(['customers', 'customers.customers']);

    $response = get(route('admin.customers.customers.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to customers index without customers.customers permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.customers.customers.index'))
        ->assertStatus(401);
});
