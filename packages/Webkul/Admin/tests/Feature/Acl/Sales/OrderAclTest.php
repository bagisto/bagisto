<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to orders index with sales.orders permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.orders']);

    get(route('admin.sales.orders.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to orders index without sales.orders permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.orders.index'))
        ->assertUnauthorized();
});
