<?php

use Webkul\Marketing\Models\SearchSynonym;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should show the search synonyms index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.search_synonyms.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store the search synonyms', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_synonyms.store'))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('terms')
        ->assertUnprocessable();
});

it('should store the newly created search synonyms', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_synonyms.store'), [
        'terms' => $term = fake()->randomElement(['jackets', 'shoes', 'footwear',  'phone', 'computers', 'electronics']),
        'name'  => $name = fake()->name(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.create.success'));

    $this->assertModelWise([
        SearchSynonym::class => [
            [
                'terms' => $term,
                'name'  => $name,
            ],
        ],
    ]);
});

it('should fail the validation with errors when certain field not provided when update the search synonyms', function () {
    // Arrange
    $searchSynonym = SearchSynonym::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_synonyms.update', $searchSynonym->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('terms')
        ->assertUnprocessable();
});

it('should update the search synonyms', function () {
    // Arrange
    $searchSynonym = SearchSynonym::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_synonyms.update'), [
        'id'    => $searchSynonym->id,
        'terms' => $term = fake()->randomElement(['jackets', 'phone', 'computers', 'electronics']),
        'name'  => $searchSynonym->name,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.edit.success'));

    $this->assertModelWise([
        SearchSynonym::class => [
            [
                'id'    => $searchSynonym->id,
                'terms' => $term,
                'name'  => $searchSynonym->name,
            ],
        ],
    ]);
});

it('should delete the search synonyms', function () {
    // Arrange
    $searchSynonym = SearchSynonym::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.search_synonyms.delete', $searchSynonym->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.edit.delete-success'));

    $this->assertDatabaseMissing('search_synonyms', [
        'id' => $searchSynonym->id,
    ]);
});

it('should mass delete the search synonyms', function () {
    // Arrange
    $searchSynonyms = SearchSynonym::factory()->count(2)->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_synonyms.mass_delete'), [
        'indices' => $searchSynonyms->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.mass-delete-success'));

    foreach ($searchSynonyms as $searchSynonym) {
        $this->assertDatabaseMissing('search_synonyms', [
            'id' => $searchSynonym->id,
        ]);
    }
});
