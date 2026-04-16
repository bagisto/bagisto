<?php

use function Pest\Laravel\get;

// Access Granted

it('should allow access to catalog rules with marketing.promotions.catalog_rules permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.catalog_rules']);

    $response = get(route('admin.marketing.promotions.catalog_rules.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow creating a catalog rule with marketing.promotions.catalog_rules.create permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.catalog_rules', 'marketing.promotions.catalog_rules.create']);

    $response = get(route('admin.marketing.promotions.catalog_rules.create'));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to catalog rules without marketing.promotions.catalog_rules permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.marketing.promotions.catalog_rules.index'))
        ->assertStatus(401);
});

it('should deny catalog rule creation without marketing.promotions.catalog_rules.create permission', function () {
    $this->loginAsAdminWithPermissions(['marketing', 'marketing.promotions', 'marketing.promotions.catalog_rules']);

    get(route('admin.marketing.promotions.catalog_rules.create'))
        ->assertStatus(401);
});
