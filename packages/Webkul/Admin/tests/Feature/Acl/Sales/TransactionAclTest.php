<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to transactions index with sales.transactions permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.transactions']);

    get(route('admin.sales.transactions.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to transactions index without sales.transactions permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.transactions.index'))
        ->assertUnauthorized();
});
