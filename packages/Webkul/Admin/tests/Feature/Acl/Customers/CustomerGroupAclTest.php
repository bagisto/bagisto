<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to customer groups with customers.groups permission', function () {
    $this->loginAsAdminWithPermissions(['customers', 'customers.groups']);

    $response = get(route('admin.customers.groups.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to customer groups without customers.groups permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.customers.groups.index'))
        ->assertStatus(401);
});
