<?php

use function Pest\Laravel\postJson;

beforeEach(function () {
    $this->address = fn (string $country, string $state): array => [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->safeEmail(),
        'address' => [fake()->streetAddress()],
        'city' => fake()->city(),
        'country' => $country,
        'state' => $state,
        'postcode' => '110001',
        'phone' => fake()->e164PhoneNumber(),
    ];
});

it('should fail the validation when the state does not belong to the selected country when storing the customer address', function (string $state) {
    // Act and Assert.
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), ($this->address)('IN', $state))
        ->assertJsonValidationErrorFor('state')
        ->assertUnprocessable();
})->with([
    'state of another country' => 'CA',
    'unknown state code' => 'XX',
]);

it('should store the customer address when the state belongs to the selected country or the country has no state list', function (string $country, string $state) {
    // Act and Assert.
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), ($this->address)($country, $state))
        ->assertRedirect(route('shop.customers.account.addresses.index'));
})->with([
    'state from the country list' => ['US', 'CA'],
    'free-form state for a country without a list' => ['GB', 'Greater London'],
]);
