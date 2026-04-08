<?php

use Webkul\Category\Models\Category;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;

// Access Granted

it('should allow access to category index with catalog.categories permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.categories']);

    $response = get(route('admin.catalog.categories.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow creating a category with catalog.categories.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.categories', 'catalog.categories.create']);

    $response = get(route('admin.catalog.categories.create'));

    expect($response->status())->not->toBe(401);
});

it('should allow editing a category with catalog.categories.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.categories', 'catalog.categories.edit']);

    $category = Category::first();

    $response = get(route('admin.catalog.categories.edit', $category->id));

    expect($response->status())->not->toBe(401);
});

it('should allow deleting a category with catalog.categories.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.categories', 'catalog.categories.delete']);

    $category = Category::factory()->create(['parent_id' => 1]);

    $response = deleteJson(route('admin.catalog.categories.delete', $category->id));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to category index without catalog.categories permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.catalog.categories.index'))
        ->assertStatus(401);
});

it('should deny category creation without catalog.categories.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.categories']);

    get(route('admin.catalog.categories.create'))
        ->assertStatus(401);
});

it('should deny category editing without catalog.categories.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.categories']);

    $category = Category::first();

    get(route('admin.catalog.categories.edit', $category->id))
        ->assertStatus(401);
});

it('should deny category deletion without catalog.categories.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.categories']);

    $category = Category::factory()->create();

    deleteJson(route('admin.catalog.categories.delete', $category->id))
        ->assertStatus(401);
});
