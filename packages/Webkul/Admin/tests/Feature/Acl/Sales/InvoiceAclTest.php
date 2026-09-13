<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to invoices index with sales.invoices permission', function () {
    $this->loginAsAdminWithPermissions(['sales', 'sales.invoices']);

    get(route('admin.sales.invoices.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to invoices index without sales.invoices permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.sales.invoices.index'))
        ->assertUnauthorized();
});
