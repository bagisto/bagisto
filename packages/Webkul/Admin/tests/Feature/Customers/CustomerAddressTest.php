<?php

use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

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
