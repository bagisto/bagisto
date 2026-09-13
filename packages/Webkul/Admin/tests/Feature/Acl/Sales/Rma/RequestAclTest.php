<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to rma requests with sales.rma.requests permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.rma', 'sales.rma.requests']);

    get(route('admin.sales.rma.requests.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to rma requests without sales.rma.requests permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.rma.requests.index'))
        ->assertUnauthorized();
});
