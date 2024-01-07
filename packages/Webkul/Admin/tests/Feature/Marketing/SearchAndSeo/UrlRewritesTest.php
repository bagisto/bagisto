<?php

use Webkul\Marketing\Models\URLRewrite;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    URLRewrite::query()->delete();
});

it('should show the url rewrite index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.url_rewrites.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.create-btn'));
});

it('should store the newly created url', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.url_rewrites.store'), [
        'entity_type'    => $entityType = fake()->randomElement(['product', 'category', 'cms_page']),
        'request_path'   => $requestPath = fake()->url(),
        'target_path'    => $targetPath = fake()->url(),
        'redirect_type'  => $redirecType = fake()->randomElement([302, 301]),
        'locale'         => $localeCode = core()->getCurrentLocale()->code,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.create.success'));

    $this->assertDatabaseHas('url_rewrites', [
        'entity_type'    => $entityType,
        'request_path'   => $requestPath,
        'target_path'    => $targetPath,
        'redirect_type'  => $redirecType,
        'locale'         => $localeCode,
    ]);
});

it('should update the existing url rewrite', function () {
    // Arrange
    $urlRewrite = URLRewrite::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.url_rewrites.update'), [
        'id'             => $urlRewrite->id,
        'entity_type'    => $entityType = fake()->randomElement(['product', 'category', 'cms_page']),
        'request_path'   => $requestPath = fake()->url(),
        'target_path'    => $targetPath = fake()->url(),
        'redirect_type'  => $redirecType = fake()->randomElement([302, 301]),
        'locale'         => $localeCode = core()->getCurrentLocale()->code,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.edit.success'));

    $this->assertDatabaseHas('url_rewrites', [
        'id'             => $urlRewrite->id,
        'entity_type'    => $entityType,
        'request_path'   => $requestPath,
        'target_path'    => $targetPath,
        'redirect_type'  => $redirecType,
        'locale'         => $localeCode,
    ]);
});

it('should delete the existing url rewrite', function () {
    // Arrange
    $urlRewrite = URLRewrite::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.url_rewrites.delete', $urlRewrite->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.edit.delete-success'));

    $this->assertDatabaseMissing('url_rewrites', [
        'id' => $urlRewrite->id,
    ]);
});

it('should mass delete the exising url rewrites', function () {
    // Arrange
    $urlRewrites = URLRewrite::factory()->count(2)->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.url_rewrites.mass_delete'), [
        'indices' => $urlRewrites->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.mass-delete-success'));

    foreach ($urlRewrites as $urlRewrite) {
        $this->assertDatabaseMissing('url_rewrites', [
            'id' => $urlRewrite->id,
        ]);
    }
});
