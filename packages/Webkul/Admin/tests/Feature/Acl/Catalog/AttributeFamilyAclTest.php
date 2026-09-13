<?php

use Webkul\Attribute\Models\AttributeFamily;

use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to attribute family index with catalog.families permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.families']);

    get(route('admin.catalog.families.index'))
        ->assertOk();
});

it('should allow creating an attribute family with catalog.families.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.families', 'catalog.families.create']);

    get(route('admin.catalog.families.create'))
        ->assertOk();
});

it('should allow editing an attribute family with catalog.families.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.families', 'catalog.families.edit']);

    $family = AttributeFamily::first();

    get(route('admin.catalog.families.edit', $family->id))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to attribute family index without catalog.families permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.catalog.families.index'))
        ->assertUnauthorized();
});

it('should deny attribute family creation without catalog.families.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.families']);

    get(route('admin.catalog.families.create'))
        ->assertUnauthorized();
});

it('should deny attribute family editing without catalog.families.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.families']);

    $family = AttributeFamily::first();

    get(route('admin.catalog.families.edit', $family->id))
        ->assertUnauthorized();
});
