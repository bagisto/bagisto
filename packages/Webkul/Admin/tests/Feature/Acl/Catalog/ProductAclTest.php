<?php

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to product index with catalog.products permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products']);

    get(route('admin.catalog.products.index'))
        ->assertOk();
});

it('should allow creating a product with catalog.products.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products', 'catalog.products.create']);

    postJson(route('admin.catalog.products.store'), [
        'type' => 'simple',
        'attribute_family_id' => 1,
        'sku' => fake()->uuid(),
    ])->assertOk();
});

it('should allow editing a product with catalog.products.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products', 'catalog.products.edit']);

    $product = $this->createSimpleProduct();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertOk();
});

it('should allow deleting a product with catalog.products.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products', 'catalog.products.delete']);

    $product = $this->createSimpleProduct();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertOk();
});

it('should allow copying a product with catalog.products.copy permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products', 'catalog.products.copy']);

    $product = $this->createSimpleProduct();

    postJson(route('admin.catalog.products.copy', $product->id))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to product index without catalog.products permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.catalog.products.index'))
        ->assertUnauthorized();
});

it('should deny product creation without catalog.products.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products']);

    postJson(route('admin.catalog.products.store'), [
        'type' => 'simple',
        'attribute_family_id' => 1,
        'sku' => fake()->uuid(),
    ])->assertUnauthorized();
});

it('should deny product editing without catalog.products.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products']);

    $product = $this->createSimpleProduct();

    get(route('admin.catalog.products.edit', $product->id))
        ->assertUnauthorized();
});

it('should deny product deletion without catalog.products.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products']);

    $product = $this->createSimpleProduct();

    deleteJson(route('admin.catalog.products.delete', $product->id))
        ->assertUnauthorized();
});

it('should deny product copying without catalog.products.copy permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.products']);

    $product = $this->createSimpleProduct();

    postJson(route('admin.catalog.products.copy', $product->id))
        ->assertUnauthorized();
});
