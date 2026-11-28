<?php

use Webkul\Core\Models\Channel;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Configuration Page
// ============================================================================

it('should display the category view page fields on the products configuration page', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['catalog', 'products']))
        ->assertOk()
        ->assertSeeText(trans('admin::app.configuration.index.catalog.products.category-view-page.title'))
        ->assertSee('name="catalog[products][category_view_page][description_position]"', false)
        ->assertSee('name="catalog[products][category_view_page][collapsible_description]"', false);
});

// ============================================================================
// Saving
// ============================================================================

it('should save the description position and the collapsible description per channel', function () {
    $this->loginAsAdmin();

    $channel = Channel::factory()->create();

    postJson(route('admin.configuration.store', ['catalog', 'products']), [
        'locale' => app()->getLocale(),
        'channel' => $channel->code,
        'catalog' => [
            'products' => [
                'category_view_page' => [
                    'description_position' => 'bottom',
                    'collapsible_description' => '1',
                ],
            ],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('success', trans('admin::app.configuration.index.save-message'));

    $this->assertDatabaseHas('core_config', [
        'code' => 'catalog.products.category_view_page.description_position',
        'channel_code' => $channel->code,
        'value' => 'bottom',
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'catalog.products.category_view_page.collapsible_description',
        'channel_code' => $channel->code,
        'value' => '1',
    ]);

    expect(core()->getConfigData('catalog.products.category_view_page.description_position', $channel->code))->toBe('bottom');
});
