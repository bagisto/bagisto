<?php

use Webkul\CMS\Models\Page;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;

// Access Granted

it('should allow access to cms index with cms permission', function () {
    $this->loginAsAdminWithPermissions(['cms']);

    $response = get(route('admin.cms.index'));

    expect($response->status())->not->toBe(401);
});

it('should allow creating a cms page with cms.create permission', function () {
    $this->loginAsAdminWithPermissions(['cms', 'cms.create']);

    $response = get(route('admin.cms.create'));

    expect($response->status())->not->toBe(401);
});

it('should allow editing a cms page with cms.edit permission', function () {
    $this->loginAsAdminWithPermissions(['cms', 'cms.edit']);

    $page = Page::first() ?? Page::factory()->create();

    $response = get(route('admin.cms.edit', $page->id));

    expect($response->status())->not->toBe(401);
});

it('should allow deleting a cms page with cms.delete permission', function () {
    $this->loginAsAdminWithPermissions(['cms', 'cms.delete']);

    $page = Page::factory()->create();

    $response = deleteJson(route('admin.cms.delete', $page->id));

    expect($response->status())->not->toBe(401);
});

// Access Denied

it('should deny access to cms index without cms permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.cms.index'))
        ->assertStatus(401);
});

it('should deny cms page creation without cms.create permission', function () {
    $this->loginAsAdminWithPermissions(['cms']);

    get(route('admin.cms.create'))
        ->assertStatus(401);
});

it('should deny cms page editing without cms.edit permission', function () {
    $this->loginAsAdminWithPermissions(['cms']);

    $page = Page::first() ?? Page::factory()->create();

    get(route('admin.cms.edit', $page->id))
        ->assertStatus(401);
});

it('should deny cms page deletion without cms.delete permission', function () {
    $this->loginAsAdminWithPermissions(['cms']);

    $page = Page::factory()->create();

    deleteJson(route('admin.cms.delete', $page->id))
        ->assertStatus(401);
});
