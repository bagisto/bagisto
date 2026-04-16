<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to refunds index with sales.refunds permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.refunds']);

    $response = get(route('admin.sales.refunds.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to refunds index without sales.refunds permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.refunds.index'))
        ->assertStatus(401);
});
