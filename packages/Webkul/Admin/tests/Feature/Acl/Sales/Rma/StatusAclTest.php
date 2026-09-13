<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to rma statuses with sales.rma.statuses permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.rma', 'sales.rma.statuses']);

    get(route('admin.sales.rma.statuses.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to rma statuses without sales.rma.statuses permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.rma.statuses.index'))
        ->assertUnauthorized();
});
