<?php

use Webkul\Attribute\Models\AttributeFamily;

use function Pest\Laravel\get;

// Access Granted

it('should allow access to attribute family index with catalog.families permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.families']);

    $response = get(route('admin.catalog.families.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow creating an attribute family with catalog.families.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.families', 'catalog.families.create']);

    $response = get(route('admin.catalog.families.create'));

    expect($response->status())->not->toBe(401);
});

it('should allow editing an attribute family with catalog.families.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.families', 'catalog.families.edit']);

    $family = AttributeFamily::first();

    $response = get(route('admin.catalog.families.edit', $family->id));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to attribute family index without catalog.families permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.catalog.families.index'))
        ->assertStatus(401);
});

it('should deny attribute family creation without catalog.families.create permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.families']);

    get(route('admin.catalog.families.create'))
        ->assertStatus(401);
});

it('should deny attribute family editing without catalog.families.edit permission', function () {
    $this->loginAsAdminWithPermissions(['catalog.families']);

    $family = AttributeFamily::first();

    get(route('admin.catalog.families.edit', $family->id))
        ->assertStatus(401);
});
