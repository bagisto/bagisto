<?php

use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

// ============================================================================
// Country List
// ============================================================================

it('should offer every country while the storefront is not limited to some', function () {
    $codes = collect(getJson(route('shop.api.core.countries'))->assertOk()->json('data'))->pluck('code');

    expect($codes)->toContain('IN', 'US', 'GB');
});

it('should offer only the allowed countries', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN,US');

    $codes = collect(getJson(route('shop.api.core.countries'))->assertOk()->json('data'))->pluck('code');

    expect($codes->all())->toEqualCanonicalizing(['IN', 'US']);
});

it('should offer the states of the allowed countries only', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    $states = getJson(route('shop.api.core.states'))->assertOk()->json('data');

    expect(array_keys($states))->toBe(['IN']);
});

it('should list the countries as options of the configuration field', function () {
    $options = app(Webkul\Core\Repositories\CountryRepository::class)->getConfigOptions();

    expect($options)->toContain(['title' => 'India', 'value' => 'IN']);
});

// ============================================================================
// Customer Address
// ============================================================================

it('should fail the validation when the country is not allowed when storing the customer address', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), $this->storefrontAddress([
        'country' => 'US',
        'state' => 'CA',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('country');
});

it('should store the customer address when the country is allowed', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN,US');

    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), $this->storefrontAddress([
        'country' => 'US',
        'state' => 'CA',
    ]))
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertDatabaseHas('addresses', [
        'customer_id' => $customer->id,
        'country' => 'US',
    ]);
});

it('should store the customer address in any country while the storefront is not limited to some', function () {
    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), $this->storefrontAddress([
        'country' => 'GB',
        'state' => 'Greater London',
    ]))
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertDatabaseHas('addresses', [
        'customer_id' => $customer->id,
        'country' => 'GB',
    ]);
});
