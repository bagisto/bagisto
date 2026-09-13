<?php

use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->addProductToCart($this->createSimpleProduct()->id);
});

it('should fail the validation when the billing state belongs to another country', function () {
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $this->storefrontAddress([
            'country' => 'IN',
            'state' => 'CA',
            'use_for_shipping' => true,
        ]),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['billing.state' => trans('validation.in', ['attribute' => 'billing.state'])]);
});

it('should fail the validation when the shipping state belongs to another country', function () {
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $this->storefrontAddress([
            'country' => 'US',
            'state' => 'CA',
            'use_for_shipping' => false,
        ]),
        'shipping' => $this->storefrontAddress([
            'country' => 'IN',
            'state' => 'CA',
        ]),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipping.state')
        ->assertJsonMissingValidationErrors('billing.state');
});

it('should fail the validation when the state is not in the state list of the selected country', function () {
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $this->storefrontAddress([
            'country' => 'IN',
            'state' => 'XX',
            'use_for_shipping' => true,
        ]),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('billing.state');
});

it('should store the address when the state belongs to the selected country or the country has no state list', function (string $country, string $state) {
    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $this->storefrontAddress([
            'country' => $country,
            'state' => $state,
            'use_for_shipping' => true,
        ]),
    ])
        ->assertOk();

    $this->assertDatabaseHas('addresses', [
        'address_type' => 'cart_billing',
        'country' => $country,
        'state' => $state,
    ]);
})->with([
    'state from the country list' => ['US', 'CA'],
    'free-form state for a country without a list' => ['GB', 'Greater London'],
]);
