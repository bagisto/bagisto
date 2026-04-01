<?php

use Webkul\CMS\Models\Page;
use Webkul\CMS\Models\PageTranslation;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

/**
 * Create a CMS page with its translation and channel assignment.
 */
function createCmsPage(array $overrides = []): Page
{
    $page = Page::factory()->create();

    PageTranslation::factory()->create(array_merge([
        'cms_page_id' => $page->id,
    ], $overrides));

    $page->channels()->sync([1]);

    return $page->fresh();
}

// ============================================================================
// Index
// ============================================================================

it('should return the CMS pages index page', function () {
    $this->loginAsAdmin();

    get(route('admin.cms.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.cms.index.title'));
});

it('should deny guest access to the CMS pages index page', function () {
    get(route('admin.cms.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Create
// ============================================================================

it('should return the CMS page create page', function () {
    $this->loginAsAdmin();

    get(route('admin.cms.create'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.cms.create.title'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created CMS page', function () {
    $this->loginAsAdmin();

    postJson(route('admin.cms.store'), [
        'page_title' => $title = fake()->sentence(),
        'url_key' => $urlKey = fake()->unique()->slug(),
        'html_content' => '<p>Test content</p>',
        'channels' => [1],
        'meta_title' => fake()->sentence(),
        'meta_keywords' => fake()->words(3, true),
        'meta_description' => fake()->sentence(),
    ])
        ->assertRedirect(route('admin.cms.index'));

    $this->assertDatabaseHas('cms_page_translations', [
        'page_title' => $title,
        'url_key' => $urlKey,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.cms.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('url_key')
        ->assertJsonValidationErrorFor('page_title')
        ->assertJsonValidationErrorFor('html_content')
        ->assertJsonValidationErrorFor('channels');
});

it('should fail validation when url_key already exists on store', function () {
    $existingPage = createCmsPage(['url_key' => 'existing-slug']);

    $this->loginAsAdmin();

    postJson(route('admin.cms.store'), [
        'page_title' => fake()->sentence(),
        'url_key' => 'existing-slug',
        'html_content' => '<p>Content</p>',
        'channels' => [1],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('url_key');
});

// ============================================================================
// Edit
// ============================================================================

it('should return the CMS page edit page', function () {
    $page = createCmsPage();

    $this->loginAsAdmin();

    get(route('admin.cms.edit', $page->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.cms.edit.title'));
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing CMS page', function () {
    $locale = app()->getLocale();

    $this->loginAsAdmin();

    // Create the page via the store route so all listeners fire properly.
    postJson(route('admin.cms.store'), [
        'page_title' => 'Original Title',
        'url_key' => $originalSlug = fake()->unique()->slug(),
        'html_content' => '<p>Original content</p>',
        'channels' => [1],
    ])
        ->assertRedirect(route('admin.cms.index'));

    $page = Page::whereHas('translations', fn ($q) => $q->where('url_key', $originalSlug))->first();

    putJson(route('admin.cms.update', ['id' => $page->id, 'locale' => $locale]), [
        $locale => [
            'page_title' => $title = fake()->sentence(),
            'url_key' => $originalSlug,
            'html_content' => '<p>Updated content</p>',
        ],
        'channels' => [1],
        'locale' => $locale,
    ])
        ->assertRedirect(route('admin.cms.index'));

    $this->assertDatabaseHas('cms_page_translations', [
        'cms_page_id' => $page->id,
        'page_title' => $title,
        'url_key' => $originalSlug,
        'locale' => $locale,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $page = createCmsPage();
    $locale = app()->getLocale();

    $this->loginAsAdmin();

    putJson(route('admin.cms.update', $page->id), [
        $locale => [],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor("{$locale}.url_key")
        ->assertJsonValidationErrorFor("{$locale}.page_title")
        ->assertJsonValidationErrorFor("{$locale}.html_content")
        ->assertJsonValidationErrorFor('channels');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a CMS page', function () {
    $page = createCmsPage();

    $this->loginAsAdmin();

    deleteJson(route('admin.cms.delete', $page->id))
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.cms.delete-success'));

    $this->assertDatabaseMissing('cms_pages', ['id' => $page->id]);
    $this->assertDatabaseMissing('cms_page_translations', ['cms_page_id' => $page->id]);
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete CMS pages', function () {
    $pages = collect([createCmsPage(), createCmsPage()]);

    $this->loginAsAdmin();

    postJson(route('admin.cms.mass_delete'), [
        'indices' => $pages->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.cms.index.datagrid.mass-delete-success'));

    foreach ($pages as $page) {
        $this->assertDatabaseMissing('cms_pages', ['id' => $page->id]);
    }
});
