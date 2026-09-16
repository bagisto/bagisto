<?php

use Webkul\Attribute\Models\Attribute;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to attribute index with catalog.attributes permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.attributes']);

    get(route('admin.catalog.attributes.index'))
        ->assertOk();
});

it('should allow creating an attribute with catalog.attributes.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.attributes', 'catalog.attributes.create']);

    get(route('admin.catalog.attributes.create'))
        ->assertOk();
});

it('should allow editing an attribute with catalog.attributes.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.attributes', 'catalog.attributes.edit']);

    $attribute = Attribute::query()->where('is_user_defined', 1)->first()
        ?? Attribute::factory()->create(['is_user_defined' => 1]);

    get(route('admin.catalog.attributes.edit', $attribute->id))
        ->assertOk();
});

it('should allow deleting an attribute with catalog.attributes.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.attributes', 'catalog.attributes.delete']);

    $attribute = Attribute::factory()->create(['is_user_defined' => 1]);

    deleteJson(route('admin.catalog.attributes.delete', $attribute->id))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to attribute index without catalog.attributes permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.catalog.attributes.index'))
        ->assertUnauthorized();
});

it('should deny attribute creation without catalog.attributes.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.attributes']);

    get(route('admin.catalog.attributes.create'))
        ->assertUnauthorized();
});

it('should deny attribute editing without catalog.attributes.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.attributes']);

    $attribute = Attribute::query()->first();

    get(route('admin.catalog.attributes.edit', $attribute->id))
        ->assertUnauthorized();
});

it('should deny attribute deletion without catalog.attributes.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.attributes']);

    $attribute = Attribute::factory()->create(['is_user_defined' => 1]);

    deleteJson(route('admin.catalog.attributes.delete', $attribute->id))
        ->assertUnauthorized();
});
