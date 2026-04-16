<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to rma custom fields with sales.rma.custom-fields permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.rma', 'sales.rma.custom-fields']);

    $response = get(route('admin.sales.rma.custom-fields.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to rma custom fields without sales.rma.custom-fields permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.rma.custom-fields.index'))
        ->assertStatus(401);
});
