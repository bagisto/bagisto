<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to sales reporting with reporting.sales permission', function () {
    $this->loginAsAdminWithPermissions(['reporting', 'reporting.sales']);

    $response = get(route('admin.reporting.sales.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow access to customers reporting with reporting.customers permission', function () {
    $this->loginAsAdminWithPermissions(['reporting', 'reporting.customers']);

    $response = get(route('admin.reporting.customers.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow access to products reporting with reporting.products permission', function () {
    $this->loginAsAdminWithPermissions(['reporting', 'reporting.products']);

    $response = get(route('admin.reporting.products.index'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to sales reporting without reporting.sales permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.reporting.sales.index'))
        ->assertStatus(401);
});

it('should deny access to customers reporting without reporting.customers permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.reporting.customers.index'))
        ->assertStatus(401);
});

it('should deny access to products reporting without reporting.products permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.reporting.products.index'))
        ->assertStatus(401);
});
