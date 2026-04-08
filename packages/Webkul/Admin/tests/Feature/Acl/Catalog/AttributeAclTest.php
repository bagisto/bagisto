<?php

use Webkul\Attribute\Models\Attribute;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;

// Access Granted

it('should allow access to attribute index with catalog.attributes permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.attributes']);

    $response = get(route('admin.catalog.attributes.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow creating an attribute with catalog.attributes.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.attributes', 'catalog.attributes.create']);

    $response = get(route('admin.catalog.attributes.create'));

    expect($response->status())->not->toBe(401);
});

it('should allow editing an attribute with catalog.attributes.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.attributes', 'catalog.attributes.edit']);

    $attribute = Attribute::where('is_user_defined', 1)->first()
        ?? Attribute::factory()->create(['is_user_defined' => 1]);

    $response = get(route('admin.catalog.attributes.edit', $attribute->id));

    expect($response->status())->not->toBe(401);
});

it('should allow deleting an attribute with catalog.attributes.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.attributes', 'catalog.attributes.delete']);

    $attribute = Attribute::factory()->create(['is_user_defined' => 1]);

    $response = deleteJson(route('admin.catalog.attributes.delete', $attribute->id));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to attribute index without catalog.attributes permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.catalog.attributes.index'))
        ->assertStatus(401);
});

it('should deny attribute creation without catalog.attributes.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.attributes']);

    get(route('admin.catalog.attributes.create'))
        ->assertStatus(401);
});

it('should deny attribute editing without catalog.attributes.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.attributes']);

    $attribute = Attribute::first();

    get(route('admin.catalog.attributes.edit', $attribute->id))
        ->assertStatus(401);
});

it('should deny attribute deletion without catalog.attributes.delete permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.attributes']);

    $attribute = Attribute::factory()->create(['is_user_defined' => 1]);

    deleteJson(route('admin.catalog.attributes.delete', $attribute->id))
        ->assertStatus(401);
});
