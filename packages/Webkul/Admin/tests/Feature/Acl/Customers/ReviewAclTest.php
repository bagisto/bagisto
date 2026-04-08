<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to reviews with customers.reviews permission', function () {
    $this->loginAsAdminWithPermissions(['customers', 'customers.reviews']);

    $response = get(route('admin.customers.customers.review.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to reviews without customers.reviews permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.customers.customers.review.index'))
        ->assertStatus(401);
});
