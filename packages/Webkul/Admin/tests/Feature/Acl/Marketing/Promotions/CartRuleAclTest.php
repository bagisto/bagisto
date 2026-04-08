<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to cart rules with marketing.promotions.cart_rules permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.cart_rules']);

    $response = get(route('admin.marketing.promotions.cart_rules.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow creating a cart rule with marketing.promotions.cart_rules.create permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.cart_rules', 'marketing.promotions.cart_rules.create']);

    $response = get(route('admin.marketing.promotions.cart_rules.create'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to cart rules without marketing.promotions.cart_rules permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.promotions.cart_rules.index'))
        ->assertStatus(401);
});

it('should deny cart rule creation without marketing.promotions.cart_rules.create permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.cart_rules']);

    get(route('admin.marketing.promotions.cart_rules.create'))
        ->assertStatus(401);
});
