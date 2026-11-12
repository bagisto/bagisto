<?php

use Webkul\Core\Models\Channel;
use Webkul\Core\Models\Locale;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Save the allowed countries for a channel the way the configuration form submits them.
 */
function saveAllowedCountries(array $countries): void
{
    $channel = Channel::factory()->create();

    $locale = Locale::factory()->create();

    postJson(route('admin.configuration.store', ['customer', 'address']), [
        'locale' => $locale->code,
        'channel' => $channel->code,
        'customer' => [
            'address' => [
                'countries' => [
                    'allowed' => $countries,
                ],
            ],
        ],
    ])
        ->assertRedirect()
        ->assertSessionHas('success', trans('admin::app.configuration.index.save-message'));
}

// ============================================================================
// Configuration Page
// ============================================================================

it('should display the allowed countries field on the address configuration page', function () {
    $this->loginAsAdmin();

    get(route('admin.configuration.index', ['customer', 'address', 'countries']))
        ->assertOk()
        ->assertSee('name="customer[address][countries][allowed]"', false)
        ->assertSee('label="'.trans('admin::app.configuration.index.customer.address.countries.allowed').'"', false)
        ->assertSee('&quot;value&quot;:&quot;IN&quot;', false);
});

// ============================================================================
// Saving
// ============================================================================

it('should save the selected countries as a comma separated list', function () {
    $this->loginAsAdmin();

    saveAllowedCountries(['IN', 'US']);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.address.countries.allowed',
        'value' => 'IN,US',
    ]);
});

it('should lift the limit when every country is deselected', function () {
    $this->loginAsAdmin();

    saveAllowedCountries(['IN']);

    saveAllowedCountries(['']);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.address.countries.allowed',
        'value' => '',
    ]);
});
