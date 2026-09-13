<?php

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to catalog rules with marketing.promotions.catalog_rules permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.catalog_rules']);

    get(route('admin.marketing.promotions.catalog_rules.index'))
        ->assertOk();
});

it('should allow creating a catalog rule with marketing.promotions.catalog_rules.create permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.catalog_rules', 'marketing.promotions.catalog_rules.create']);

    get(route('admin.marketing.promotions.catalog_rules.create'))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to catalog rules without marketing.promotions.catalog_rules permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.promotions.catalog_rules.index'))
        ->assertUnauthorized();
});

it('should deny catalog rule creation without marketing.promotions.catalog_rules.create permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.catalog_rules']);

    get(route('admin.marketing.promotions.catalog_rules.create'))
        ->assertUnauthorized();
});
