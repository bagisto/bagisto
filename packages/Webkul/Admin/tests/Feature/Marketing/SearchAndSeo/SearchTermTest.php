<?php

use Webkul\Marketing\Models\SearchTerm;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

it('should show the search terms index page', function () {
    // Act and Assert
    $this->loginAsAdmin();

    get(route('admin.marketing.search_seo.search_terms.index'))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.title'))
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.create-btn'));
});

it('should fail the validation with errors when certain field not provided when store the search term', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_terms.store'))
        ->assertJsonValidationErrorFor('term')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('locale')
        ->assertUnprocessable();
});

it('should store the newly created search term', function () {
    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_terms.store'), [
        'term'         => $term = fake()->randomElement(['jackets', 'phone', 'computers', 'electronics']),
        'redirect_url' => $url = fake()->url(),
        'channel_id'   => $channelId = core()->getCurrentChannel()->id,
        'locale'       => $locale = core()->getCurrentLocale()->code,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.create.success'));

    $this->assertModelWise([
        SearchTerm::class => [
            [
                'term'         => $term,
                'redirect_url' => $url,
                'channel_id'   => $channelId,
                'locale'       => $locale,
            ],
        ],
    ]);
});

it('should fail the validation with errors when certain field not provided when update the search term', function () {
    // Arrange
    $searchTerm = SearchTerm::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_terms.update', $searchTerm->id))
        ->assertJsonValidationErrorFor('term')
        ->assertJsonValidationErrorFor('channel_id')
        ->assertJsonValidationErrorFor('locale')
        ->assertUnprocessable();
});

it('should update the search term', function () {
    // Arrange
    $searchTerm = SearchTerm::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    putJson(route('admin.marketing.search_seo.search_terms.update'), [
        'id'         => $searchTerm->id,
        'term'       => $term = fake()->randomElement(['jackets', 'phone', 'computers', 'electronics']),
        'channel_id' => $channelId = core()->getCurrentChannel()->id,
        'locale'     => $locale = core()->getCurrentLocale()->code,
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.edit.success'));

    $this->assertModelWise([
        SearchTerm::class => [
            [
                'id'         => $searchTerm->id,
                'term'       => $term,
                'channel_id' => $channelId,
                'locale'     => $locale,
            ],
        ],
    ]);
});

it('should delete the search term', function () {
    // Arrange
    $searchTerm = SearchTerm::factory()->create();

    // Act and Assert
    $this->loginAsAdmin();

    deleteJson(route('admin.marketing.search_seo.search_terms.delete', $searchTerm->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.edit.delete-success'));

    $this->assertDatabaseMissing('search_terms', [
        'id' => $searchTerm->id,
    ]);
});

it('should mass delete the search term', function () {
    // Arrange
    $searchTerms = SearchTerm::factory()->count(2)->create();

    // Act and Assert
    $this->loginAsAdmin();

    postJson(route('admin.marketing.search_seo.search_terms.mass_delete'), [
        'indices' => $searchTerms->pluck('id')->toArray(),
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.marketing.search-seo.search-terms.index.datagrid.mass-delete-success'));

    foreach ($searchTerms as $searchTerm) {
        $this->assertDatabaseMissing('search_terms', [
            'id' => $searchTerm->id,
        ]);
    }
});
