<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to cart rules with marketing.promotions.cart_rules permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.cart_rules']);

    get(route('admin.marketing.promotions.cart_rules.index'))
        ->assertOk();
});

it('should allow creating a cart rule with marketing.promotions.cart_rules.create permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.cart_rules', 'marketing.promotions.cart_rules.create']);

    get(route('admin.marketing.promotions.cart_rules.create'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to cart rules without marketing.promotions.cart_rules permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.promotions.cart_rules.index'))
        ->assertUnauthorized();
});

it('should deny cart rule creation without marketing.promotions.cart_rules.create permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.cart_rules']);

    get(route('admin.marketing.promotions.cart_rules.create'))
        ->assertUnauthorized();
});
