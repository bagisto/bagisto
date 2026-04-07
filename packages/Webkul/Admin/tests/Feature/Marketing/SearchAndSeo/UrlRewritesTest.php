<?php

use Webkul\Marketing\Models\URLRewrite;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the URL rewrites index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.url_rewrites.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.create-btn'));
});

it('should deny guest access to the URL rewrites index page', function () {
    get(route('admin.marketing.search_seo.url_rewrites.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created URL rewrite', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.url_rewrites.store'), [
        'entity_type' => $entityType = fake()->randomElement(['product', 'category', 'cms_page']),
        'request_path' => $requestPath = fake()->url(),
        'target_path' => $targetPath = fake()->url(),
        'redirect_type' => $redirectType = fake()->randomElement([301, 302]),
        'locale' => $locale = core()->getCurrentLocale()->code,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.create.success'));

    $this->assertDatabaseHas('url_rewrites', [
        'entity_type' => $entityType,
        'request_path' => $requestPath,
        'target_path' => $targetPath,
        'redirect_type' => $redirectType,
        'locale' => $locale,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.url_rewrites.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('entity_type')
        ->assertJsonValidationErrorFor('request_path')
        ->assertJsonValidationErrorFor('target_path')
        ->assertJsonValidationErrorFor('redirect_type')
        ->assertJsonValidationErrorFor('locale');
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing URL rewrite', function () {
    $rewrite = URLRewrite::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.url_rewrites.update'), [
        'id' => $rewrite->id,
        'entity_type' => $entityType = fake()->randomElement(['product', 'category', 'cms_page']),
        'request_path' => $requestPath = fake()->url(),
        'target_path' => $targetPath = fake()->url(),
        'redirect_type' => $redirectType = fake()->randomElement([301, 302]),
        'locale' => $locale = core()->getCurrentLocale()->code,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.edit.success'));

    $this->assertDatabaseHas('url_rewrites', [
        'id' => $rewrite->id,
        'entity_type' => $entityType,
        'redirect_type' => $redirectType,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $rewrite = URLRewrite::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.url_rewrites.update', $rewrite->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('entity_type')
        ->assertJsonValidationErrorFor('request_path')
        ->assertJsonValidationErrorFor('target_path')
        ->assertJsonValidationErrorFor('redirect_type')
        ->assertJsonValidationErrorFor('locale');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a URL rewrite', function () {
    $rewrite = URLRewrite::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.url_rewrites.delete', $rewrite->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.edit.delete-success'));

    $this->assertDatabaseMissing('url_rewrites', ['id' => $rewrite->id]);
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete URL rewrites', function () {
    $rewrites = URLRewrite::factory()->count(2)->create();

    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.url_rewrites.mass_delete'), [
        'indices' => $rewrites->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.url-rewrites.index.datagrid.mass-delete-success'));

    foreach ($rewrites as $rewrite) {
        $this->assertDatabaseMissing('url_rewrites', ['id' => $rewrite->id]);
    }
});
