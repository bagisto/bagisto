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
    $searchsynonym = SearchSynonym::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_synonyms.update', $searchsynonym->id))
        ->assertJsonValidationErrorFor('name')
        ->assertJsonValidationErrorFor('terms')
        ->assertUnprocessable();
});

it('should update the search synonyms', function () {
    // Arrange
    $searchsynonym = SearchSynonym::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_synonyms.update'), [
        'id'    => $searchsynonym->id,
        'terms' => $term = fake()->randomElement(['jackets', 'phone', 'computers', 'electronics']),
        'name'  => $searchsynonym->name,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.edit.success'));

    $this->assertModelWise([
        SearchSynonym::class => [
            [
                'id'    => $searchsynonym->id,
                'terms' => $term,
                'name'  => $searchsynonym->name,
            ],
        ],
    ]);
});

it('should delete the search synonyms', function () {
    // Arrange
    $searchsynonym = SearchSynonym::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.search_synonyms.delete', $searchsynonym->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.edit.delete-success'));

    $this->assertDatabaseMissing('search_synonyms', [
        'id' => $searchsynonym->id,
    ]);
});

it('should mass delete the search synonyms', function () {
    // Arrange
    $searchsynonyms = SearchSynonym::factory()->count(2)->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_synonyms.mass_delete'), [
        'indices' => $searchsynonyms->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-synonyms.index.datagrid.mass-delete-success'));

    foreach ($searchsynonyms as $searchsynonym) {
        $this->assertDatabaseMissing('search_synonyms', [
            'id' => $searchsynonym->id,
        ]);
    }
});
