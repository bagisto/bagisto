<?php

use Webkul\Marketing\Models\SearchTerm;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the search terms index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.search_terms.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.create-btn'));
});

it('should deny guest access to the search terms index page', function () {
    get(route('admin.marketing.search_seo.search_terms.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created search term', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_terms.store'), [
        'term' => $term = fake()->word(),
        'redirect_url' => $url = fake()->url(),
        'channel_id' => $channelId = core()->getCurrentChannel()->id,
        'locale' => $locale = core()->getCurrentLocale()->code,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.create.success'));

    $this->assertDatabaseHas('search_terms', [
        'term' => $term,
        'redirect_url' => $url,
        'channel_id' => $channelId,
        'locale' => $locale,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_terms.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('term')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('locale');
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing search term', function () {
    $searchTerm = SearchTerm::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_terms.update'), [
        'id' => $searchTerm->id,
        'term' => $term = fake()->word(),
        'channel_id' => core()->getCurrentChannel()->id,
        'locale' => core()->getCurrentLocale()->code,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.edit.success'));

    $this->assertDatabaseHas('search_terms', [
        'id' => $searchTerm->id,
        'term' => $term,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $searchTerm = SearchTerm::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_terms.update', $searchTerm->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('term')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('locale');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a search term', function () {
    $searchTerm = SearchTerm::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.search_terms.delete', $searchTerm->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.edit.delete-success'));

    $this->assertDatabaseMissing('search_terms', ['id' => $searchTerm->id]);
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete search terms', function () {
    $searchTerms = SearchTerm::factory()->count(2)->create();

    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_terms.mass_delete'), [
        'indices' => $searchTerms->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.datagrid.mass-delete-success'));

    foreach ($searchTerms as $searchTerm) {
        $this->assertDatabaseMissing('search_terms', ['id' => $searchTerm->id]);
    }
});
