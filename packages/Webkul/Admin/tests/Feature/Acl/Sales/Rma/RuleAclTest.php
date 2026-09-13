<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to rma rules with sales.rma.rules permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.rma', 'sales.rma.rules']);

    get(route('admin.sales.rma.rules.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to rma rules without sales.rma.rules permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.rma.rules.index'))
        ->assertUnauthorized();
});
