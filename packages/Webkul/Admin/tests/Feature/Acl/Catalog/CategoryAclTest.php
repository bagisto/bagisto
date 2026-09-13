<?php

use Webkul\Category\Models\Category;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to category index with catalog.categories permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.categories']);

    get(route('admin.catalog.categories.index'))
        ->assertOk();
});

it('should allow creating a category with catalog.categories.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.categories', 'catalog.categories.create']);

    get(route('admin.catalog.categories.create'))
        ->assertOk();
});

it('should allow editing a category with catalog.categories.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.categories', 'catalog.categories.edit']);

    $category = Category::first();

    get(route('admin.catalog.categories.edit', $category->id))
        ->assertOk();
});

it('should allow deleting a category with catalog.categories.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.categories', 'catalog.categories.delete']);

    $category = Category::factory()->create();

    deleteJson(route('admin.catalog.categories.delete', $category->id))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to category index without catalog.categories permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.catalog.categories.index'))
        ->assertUnauthorized();
});

it('should deny category creation without catalog.categories.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.categories']);

    get(route('admin.catalog.categories.create'))
        ->assertUnauthorized();
});

it('should deny category editing without catalog.categories.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.categories']);

    $category = Category::first();

    get(route('admin.catalog.categories.edit', $category->id))
        ->assertUnauthorized();
});

it('should deny category deletion without catalog.categories.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog', 'catalog.categories']);

    $category = Category::factory()->create();

    deleteJson(route('admin.catalog.categories.delete', $category->id))
        ->assertUnauthorized();
});
