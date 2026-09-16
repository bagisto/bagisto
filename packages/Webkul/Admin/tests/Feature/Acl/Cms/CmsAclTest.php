<?php

use Webkul\CMS\Models\Page;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;

// ============================================================================
// Access Granted
// ============================================================================

it('should allow access to cms index with cms permission', function () {
    $this->loginAsAdminWithPermissions(['cms']);

    get(route('admin.cms.index'))
        ->assertOk();
});

it('should allow creating a cms page with cms.create permission', function () {
    $this->loginAsAdminWithPermissions(['cms', 'cms.create']);

    get(route('admin.cms.create'))
        ->assertOk();
});

it('should allow editing a cms page with cms.edit permission', function () {
    $this->loginAsAdminWithPermissions(['cms', 'cms.edit']);

    $page = Page::query()->first() ?? Page::factory()->create();

    get(route('admin.cms.edit', $page->id))
        ->assertOk();
});

it('should allow deleting a cms page with cms.delete permission', function () {
    $this->loginAsAdminWithPermissions(['cms', 'cms.delete']);

    $page = Page::factory()->create();

    deleteJson(route('admin.cms.delete', $page->id))
        ->assertOk();
});

// ============================================================================
// Access Denied
// ============================================================================

it('should deny access to cms index without cms permission', function () {
    $this->loginAsAdminWithPermissions(['dashboard']);

    get(route('admin.cms.index'))
        ->assertUnauthorized();
});

it('should deny cms page creation without cms.create permission', function () {
    $this->loginAsAdminWithPermissions(['cms']);

    get(route('admin.cms.create'))
        ->assertUnauthorized();
});

it('should deny cms page editing without cms.edit permission', function () {
    $this->loginAsAdminWithPermissions(['cms']);

    $page = Page::query()->first() ?? Page::factory()->create();

    get(route('admin.cms.edit', $page->id))
        ->assertUnauthorized();
});

it('should deny cms page deletion without cms.delete permission', function () {
    $this->loginAsAdminWithPermissions(['cms']);

    $page = Page::factory()->create();

    deleteJson(route('admin.cms.delete', $page->id))
        ->assertUnauthorized();
});
