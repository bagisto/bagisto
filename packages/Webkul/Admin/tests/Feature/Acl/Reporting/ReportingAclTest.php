<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to sales reporting with reporting.sales permission', function () {
    $this->loginAsAdminWithPermissions(['reporting', 'reporting.sales']);

    get(route('admin.reporting.sales.index'))
        ->assertOk();
});

it('should allow access to customers reporting with reporting.customers permission', function () {
    $this->loginAsAdminWithPermissions(['reporting', 'reporting.customers']);

    get(route('admin.reporting.customers.index'))
        ->assertOk();
});

it('should allow access to products reporting with reporting.products permission', function () {
    $this->loginAsAdminWithPermissions(['reporting', 'reporting.products']);

    get(route('admin.reporting.products.index'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to sales reporting without reporting.sales permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.reporting.sales.index'))
        ->assertUnauthorized();
});

it('should deny access to customers reporting without reporting.customers permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.reporting.customers.index'))
        ->assertUnauthorized();
});

it('should deny access to products reporting without reporting.products permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.reporting.products.index'))
        ->assertUnauthorized();
});
