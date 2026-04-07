<?php

use Webkul\Sitemap\Models\Sitemap;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the sitemap index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.sitemaps.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.create-btn'));
});

it('should deny guest access to the sitemap index page', function () {
    get(route('admin.marketing.search_seo.sitemaps.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created sitemap', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.sitemaps.store'), [
        'file_name' => $fileName = strtolower(fake()->word()).'.xml',
        'path' => '/',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.create.success'));

    $this->assertDatabaseHas('sitemaps', [
        'file_name' => $fileName,
        'path' => '/',
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.sitemaps.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('file_name')
        ->assertJsonValidationErrorFor('path');
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing sitemap', function () {
    $sitemap = Sitemap::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.sitemaps.update'), [
        'id' => $sitemap->id,
        'file_name' => $fileName = strtolower(fake()->word()).'.xml',
        'path' => $sitemap->path,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.edit.success'));

    $this->assertDatabaseHas('sitemaps', [
        'id' => $sitemap->id,
        'file_name' => $fileName,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $sitemap = Sitemap::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.sitemaps.update', $sitemap->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('file_name')
        ->assertJsonValidationErrorFor('path');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a sitemap', function () {
    $sitemap = Sitemap::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.sitemaps.delete', $sitemap->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.edit.delete-success'));

    $this->assertDatabaseMissing('sitemaps', ['id' => $sitemap->id]);
});
