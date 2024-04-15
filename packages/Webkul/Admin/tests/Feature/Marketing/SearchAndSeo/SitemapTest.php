<?php

use Webkul\Sitemap\Models\Sitemap;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should show the sitemap index page', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.sitemaps.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store in the sitemap', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.sitemaps.store'))
        ->assertJsonValidationErrorFor('file_name')
        ->assertJsonValidationErrorFor('path')
        ->assertUnprocessable();
});

it('should store the newly created sitemap', function () {
    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.sitemaps.store'), [
        'file_name' => $fileName = strtolower(fake()->word()).'.xml',
        'path'      => $filePath = '/',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.create.success'));

    $this->assertModelWise([
        Sitemap::class => [
            [
                'file_name' => $fileName,
                'path'      => $filePath,
            ],
        ],
    ]);
});

it('should fail the validation with errors when certain field not provided when update in the sitemap', function () {
    // Arrange.
    $sitemap = Sitemap::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.sitemaps.update', $sitemap->id))
        ->assertJsonValidationErrorFor('file_name')
        ->assertJsonValidationErrorFor('path')
        ->assertUnprocessable();
});

it('should update the sitemap', function () {
    // Arrange.
    $sitemap = Sitemap::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.sitemaps.update'), [
        'id'        => $sitemap->id,
        'file_name' => $fileName = strtolower(fake()->word()).'.xml',
        'path'      => $sitemap->path,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.edit.success'));

    $this->assertModelWise([
        Sitemap::class => [
            [
                'id'        => $sitemap->id,
                'file_name' => $fileName,
                'path'      => $sitemap->path,
            ],
        ],
    ]);
});

it('should delete the sitemap', function () {
    // Arrange.
    $sitemap = Sitemap::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.sitemaps.delete', $sitemap->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.sitemaps.index.edit.delete-success'));

    $this->assertDatabaseMissing('sitemaps', [
        'id' => $sitemap->id,
    ]);
});
