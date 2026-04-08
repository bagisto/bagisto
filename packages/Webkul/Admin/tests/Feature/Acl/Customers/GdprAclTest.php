<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to gdpr requests with customers.gdpr_requests permission', function () {
    $this->loginAsAdminWithPermissions(['customers', 'customers.gdpr_requests']);

    $response = get(route('admin.customers.gdpr.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to gdpr requests without customers.gdpr_requests permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.customers.gdpr.index'))
        ->assertStatus(401);
});
