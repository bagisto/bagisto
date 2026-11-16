<?php

use Webkul\Core\Models\Channel;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Save the allowed countries of a channel as the form posts them, an empty value once none is chosen.
 */
function saveAllowedCountries(Channel $channel, array $countries): void
{
    postJson(route('admin.configuration.store', ['customer', 'address']), [
        'locale' => app()->getLocale(),
        'channel' => $channel->code,
        'customer' => [
            'address' => [
                'countries' => [
                    'allowed' => $countries ?: '',
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

    $channel = Channel::factory()->create();

    saveAllowedCountries($channel, ['IN', 'US']);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.address.countries.allowed',
        'channel_code' => $channel->code,
        'value' => 'IN,US',
    ]);
});

it('should lift the limit of a channel when every country is deselected', function () {
    $this->loginAsAdmin();

    $channel = Channel::factory()->create();

    saveAllowedCountries($channel, ['IN']);

    saveAllowedCountries($channel, []);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.address.countries.allowed',
        'channel_code' => $channel->code,
        'value' => '',
    ]);

    $this->assertDatabaseMissing('core_config', [
        'code' => 'customer.address.countries.allowed',
        'channel_code' => $channel->code,
        'value' => 'IN',
    ]);
});

it('should keep the limit of one channel apart from another', function () {
    $this->loginAsAdmin();

    $limited = Channel::factory()->create();

    $other = Channel::factory()->create();

    saveAllowedCountries($limited, ['IN']);

    saveAllowedCountries($other, ['US']);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.address.countries.allowed',
        'channel_code' => $limited->code,
        'value' => 'IN',
    ]);

    $this->assertDatabaseHas('core_config', [
        'code' => 'customer.address.countries.allowed',
        'channel_code' => $other->code,
        'value' => 'US',
    ]);
});
