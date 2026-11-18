<?php

use Webkul\Core\Models\Channel;
use Webkul\Core\Models\CoreConfig;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;
use function Pest\Laravel\put;
use function Pest\Laravel\putJson;

/**
 * Create a customer on a channel of their own, carrying the address settings given.
 */
function customerOnChannelWith(array $settings): Customer
{
    $channel = Channel::factory()->create();

    foreach ($settings as $code => $value) {
        CoreConfig::factory()->create([
            'code' => $code,
            'value' => $value,
            'channel_code' => $channel->code,
        ]);
    }

    return Customer::factory()->create(['channel_id' => $channel->id]);
}

/**
 * The address the admin modal posts for the given customer.
 */
function adminAddressPayload(Customer $customer, array $attributes = []): array
{
    return array_merge([
        'first_name' => 'Demo',
        'last_name' => 'Customer',
        'email' => $customer->email,
        'address' => ['1 Test Street'],
        'country' => 'IN',
        'state' => 'UP',
        'city' => 'Noida',
        'postcode' => '201301',
        'phone' => '1234567890',
    ], $attributes);
}

beforeEach(function () {
    $this->loginAsAdmin();
});

// ============================================================================
// Reaching The Addresses
// ============================================================================

it('should send the addresses url to the customer the addresses belong to', function () {
    $customer = Customer::factory()->create();

    get(route('admin.customers.customers.addresses.index', $customer->id))
        ->assertRedirect(route('admin.customers.customers.view', $customer->id));
});

it('should answer not found for the addresses of a customer that does not exist', function () {
    get(route('admin.customers.customers.addresses.index', 999999))->assertNotFound();
});

// ============================================================================
// Managing Them From The Customer Screen
// ============================================================================

it('should still record an address through the modal', function () {
    $customer = Customer::factory()->create();

    post(route('admin.customers.customers.addresses.store', $customer->id), [
        'customer_id' => $customer->id,
        'company_name' => 'Webkul',
        'first_name' => 'Demo',
        'last_name' => 'Customer',
        'email' => $customer->email,
        'address' => ['1 Test Street'],
        'country' => 'IN',
        'state' => 'UP',
        'city' => 'Noida',
        'postcode' => '201301',
        'phone' => '1234567890',
    ])->assertOk();

    expect(CustomerAddress::where('customer_id', $customer->id)->count())->toBe(1);
});

it('should still make an address the default through the modal', function () {
    $customer = Customer::factory()->create();

    $address = CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    post(route('admin.customers.customers.addresses.set_default', $customer->id), [
        'set_as_default' => $address->id,
    ])->assertOk();

    expect((bool) $address->refresh()->default_address)->toBeTrue();
});

it('should answer not found when the address to default does not belong to the customer', function () {
    $customer = Customer::factory()->create();

    $other = CustomerAddress::factory()->create(['default_address' => 0]);

    post(route('admin.customers.customers.addresses.set_default', $customer->id), [
        'set_as_default' => $other->id,
    ])->assertNotFound();

    expect((bool) $other->refresh()->default_address)->toBeFalse();
});

it('should still remove an address through the modal', function () {
    $customer = Customer::factory()->create();

    $address = CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    post(route('admin.customers.customers.addresses.delete', $address->id))->assertOk();

    expect(CustomerAddress::find($address->id))->toBeNull();
});

// ============================================================================
// The Owning Customer Comes From The Record, Not The Request
// ============================================================================

it('should keep an address with its own customer when the request names another', function () {
    $customer = Customer::factory()->create();

    $other = Customer::factory()->create();

    $address = CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    put(route('admin.customers.customers.addresses.update', $address->id), [
        'customer_id' => $other->id,
        'first_name' => 'Demo',
        'last_name' => 'Customer',
        'email' => $customer->email,
        'address' => ['1 Test Street'],
        'country' => 'IN',
        'state' => 'UP',
        'city' => 'Noida',
        'postcode' => '201301',
        'phone' => '1234567890',
    ])->assertOk();

    expect($address->refresh()->customer_id)->toBe($customer->id);
});

it('should leave another customer default address alone when updating one of its own', function () {
    $customer = Customer::factory()->create();

    $other = Customer::factory()->create();

    $default = CustomerAddress::factory()->create([
        'customer_id' => $other->id,
        'default_address' => 1,
    ]);

    $address = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
        'default_address' => 0,
    ]);

    put(route('admin.customers.customers.addresses.update', $address->id), [
        'customer_id' => $other->id,
        'default_address' => 1,
        'first_name' => 'Demo',
        'last_name' => 'Customer',
        'email' => $customer->email,
        'address' => ['1 Test Street'],
        'country' => 'IN',
        'state' => 'UP',
        'city' => 'Noida',
        'postcode' => '201301',
        'phone' => '1234567890',
    ])->assertOk();

    expect((bool) $default->refresh()->default_address)->toBeTrue()
        ->and((bool) $address->refresh()->default_address)->toBeTrue();
});

it('should leave another customer default address alone when recording one through the modal', function () {
    $customer = Customer::factory()->create();

    $other = Customer::factory()->create();

    $default = CustomerAddress::factory()->create([
        'customer_id' => $other->id,
        'default_address' => 1,
    ]);

    post(route('admin.customers.customers.addresses.store', $customer->id), [
        'customer_id' => $other->id,
        'default_address' => 1,
        'first_name' => 'Demo',
        'last_name' => 'Customer',
        'email' => $customer->email,
        'address' => ['1 Test Street'],
        'country' => 'IN',
        'state' => 'UP',
        'city' => 'Noida',
        'postcode' => '201301',
        'phone' => '1234567890',
    ])->assertOk();

    expect((bool) $default->refresh()->default_address)->toBeTrue()
        ->and(CustomerAddress::where('customer_id', $customer->id)->count())->toBe(1);
});

it('should answer not found when recording an address for a customer that does not exist', function () {
    post(route('admin.customers.customers.addresses.store', 999999), [
        'first_name' => 'Demo',
        'last_name' => 'Customer',
        'email' => 'demo@example.com',
        'address' => ['1 Test Street'],
        'country' => 'IN',
        'state' => 'UP',
        'city' => 'Noida',
        'postcode' => '201301',
        'phone' => '1234567890',
    ])->assertNotFound();
});

// ============================================================================
// Validation
// ============================================================================

it('should refuse street lines that together run past the address column', function () {
    $customer = Customer::factory()->create();

    postJson(route('admin.customers.customers.addresses.store', $customer->id), [
        'first_name' => 'Demo',
        'last_name' => 'Customer',
        'email' => $customer->email,
        'address' => [str_repeat('a', 200), str_repeat('b', 60)],
        'country' => 'IN',
        'state' => 'UP',
        'city' => 'Noida',
        'postcode' => '201301',
        'phone' => '1234567890',
    ])
        ->assertUnprocessable()
        ->assertOnlyJsonValidationErrors(['address']);

    expect(CustomerAddress::where('customer_id', $customer->id)->count())->toBe(0);
});

// ============================================================================
// The Customer Channel Decides The Address Settings
// ============================================================================

it('should offer the street lines and countries of the customer channel on the address modals', function () {
    $customer = customerOnChannelWith([
        'customer.address.information.street_lines' => 3,
        'customer.address.countries.allowed' => 'IN',
    ]);

    get(route('admin.customers.customers.view', $customer->id))
        ->assertOk()
        ->assertSee('placeholder="'.trans('admin::app.customers.customers.view.address.create.street-address-line', ['line' => 3]).'"', false)
        ->assertSee('placeholder="'.trans('admin::app.customers.customers.view.address.edit.street-address-line', ['line' => 3]).'"', false)
        ->assertSee('<option value="IN">', false)
        ->assertDontSee('<option value="US">', false);
});

it('should refuse an address in a country the customer channel does not allow', function () {
    $customer = customerOnChannelWith(['customer.address.countries.allowed' => 'IN']);

    postJson(route('admin.customers.customers.addresses.store', $customer->id), adminAddressPayload($customer, [
        'country' => 'US',
        'state' => 'CA',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['country' => trans('customer::app.validations.allowed-country.not-allowed')]);

    expect(CustomerAddress::where('customer_id', $customer->id)->count())->toBe(0);
});

it('should refuse to move an address to a country the customer channel does not allow', function () {
    $customer = customerOnChannelWith(['customer.address.countries.allowed' => 'IN']);

    $address = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
        'country' => 'IN',
        'state' => 'UP',
    ]);

    putJson(route('admin.customers.customers.addresses.update', $address->id), adminAddressPayload($customer, [
        'country' => 'US',
        'state' => 'CA',
    ]))
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['country' => trans('customer::app.validations.allowed-country.not-allowed')]);

    expect($address->refresh()->country)->toBe('IN');
});

it('should check the country against the customer channel rather than the default one', function () {
    $this->setConfig('customer.address.countries.allowed', 'IN');

    $customer = customerOnChannelWith([]);

    postJson(route('admin.customers.customers.addresses.store', $customer->id), adminAddressPayload($customer, [
        'country' => 'US',
        'state' => 'CA',
    ]))->assertOk();

    expect(CustomerAddress::where('customer_id', $customer->id)->value('country'))->toBe('US');
});
