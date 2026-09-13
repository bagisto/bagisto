<?php

use function Pest\Laravel\postJson;

it('should fail the validation when the state does not belong to the selected country when storing the customer address', function (string $state) {
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), $this->storefrontAddress([
        'country' => 'IN',
        'state' => $state,
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('state');
})->with([
    'state of another country' => 'CA',
    'unknown state code' => 'XX',
]);

it('should store the customer address when the state belongs to the selected country or the country has no state list', function (string $country, string $state) {
    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), $this->storefrontAddress([
        'country' => $country,
        'state' => $state,
    ]))
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertDatabaseHas('addresses', [
        'customer_id' => $customer->id,
        'country' => $country,
        'state' => $state,
    ]);
})->with([
    'state from the country list' => ['US', 'CA'],
    'free-form state for a country without a list' => ['GB', 'Greater London'],
]);
