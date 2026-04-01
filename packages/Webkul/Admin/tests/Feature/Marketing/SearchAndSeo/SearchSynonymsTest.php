<?php

use Webkul\Marketing\Models\SearchSynonym;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Index
// ============================================================================

it('should return the search synonyms index page', function () {
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.search_synonyms.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.create-btn'));
});

it('should deny guest access to the search synonyms index page', function () {
    get(route('admin.marketing.search_seo.search_synonyms.index'))
        ->assertRedirect(route('admin.session.create'));
});

// ============================================================================
// Store
// ============================================================================

it('should store a newly created search synonym', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_synonyms.store'), [
        'name' => $name = fake()->words(2, true),
        'terms' => $terms = 'sneakers, trainers, running shoes',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.create.success'));

    $this->assertDatabaseHas('search_synonyms', [
        'name' => $name,
        'terms' => $terms,
    ]);
});

it('should fail validation when required fields are missing on store', function () {
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_synonyms.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('terms');
});

// ============================================================================
// Update
// ============================================================================

it('should update an existing search synonym', function () {
    $synonym = SearchSynonym::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_synonyms.update'), [
        'id' => $synonym->id,
        'name' => $synonym->name,
        'terms' => $terms = 'laptop, notebook, ultrabook',
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.edit.success'));

    $this->assertDatabaseHas('search_synonyms', [
        'id' => $synonym->id,
        'terms' => $terms,
    ]);
});

it('should fail validation when required fields are missing on update', function () {
    $synonym = SearchSynonym::factory()->create();

    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_synonyms.update', $synonym->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('terms');
});

// ============================================================================
// Delete
// ============================================================================

it('should delete a search synonym', function () {
    $synonym = SearchSynonym::factory()->create();

    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.search_synonyms.delete', $synonym->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.edit.delete-success'));

    $this->assertDatabaseMissing('search_synonyms', ['id' => $synonym->id]);
});

// ============================================================================
// Mass Delete
// ============================================================================

it('should mass delete search synonyms', function () {
    $synonyms = SearchSynonym::factory()->count(2)->create();

    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_synonyms.mass_delete'), [
        'indices' => $synonyms->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.mass-delete-success'));

    foreach ($synonyms as $synonym) {
        $this->assertDatabaseMissing('search_synonyms', ['id' => $synonym->id]);
    }
});
