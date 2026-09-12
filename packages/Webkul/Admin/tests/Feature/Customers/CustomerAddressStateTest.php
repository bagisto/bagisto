<?php

use Webkul\Customer\Models\Customer;

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
    // Arrange.
    $customer = Customer::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.addresses.store', $customer->id), ($this->address)('IN', $state))
        ->assertJsonValidationErrorFor('state')
        ->assertUnprocessable();
})->with([
    'state of another country' => 'CA',
    'unknown state code' => 'XX',
]);

it('should store the customer address when the state belongs to the selected country or the country has no state list', function (string $country, string $state) {
    // Arrange.
    $customer = Customer::factory()->create();

    // Act and Assert.
    $this->loginAsAdmin();

    postJson(route('admin.customers.customers.addresses.store', $customer->id), ($this->address)($country, $state))
        ->assertOk();
})->with([
    'state from the country list' => ['US', 'CA'],
    'free-form state for a country without a list' => ['GB', 'Greater London'],
]);
