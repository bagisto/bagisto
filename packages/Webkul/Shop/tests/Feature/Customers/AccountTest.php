<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Product\Models\ProductReview;
use Webkul\Shop\Mail\Customer\ResetPasswordNotification;
use Webkul\Shop\Mail\Customer\UpdatePasswordNotification;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\patchJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Profile
// ============================================================================

it('should return the profile page', function () {
    $customer = $this->loginAsCustomer();

    get(route('shop.customers.account.profile.index'))
        ->assertOk()
        ->assertSeeText($customer->first_name)
        ->assertSeeText($customer->email);
});

it('should return the profile edit page', function () {
    $customer = $this->loginAsCustomer();

    get(route('shop.customers.account.profile.edit'))
        ->assertOk()
        ->assertSeeText($customer->email);
});

it('should update the customer profile', function () {
    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.profile.update'), [
        'first_name' => $firstName = fake()->firstName(),
        'last_name' => $lastName = fake()->lastName(),
        'gender' => 'Male',
        'email' => $customer->email,
        'phone' => fake()->e164PhoneNumber(),
        'date_of_birth' => now()->subYear(20)->toDateString(),
    ])
        ->assertRedirect(route('shop.customers.account.profile.index'));

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'first_name' => $firstName,
        'last_name' => $lastName,
    ]);
});

it('should update the customer password and send email', function () {
    Mail::fake();

    $customer = Customer::factory()->create([
        'password' => Hash::make($currentPassword = fake()->password(8, 10)),
    ]);

    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.profile.update'), [
        'first_name' => $customer->first_name,
        'last_name' => $customer->last_name,
        'gender' => 'Male',
        'email' => $customer->email,
        'phone' => fake()->e164PhoneNumber(),
        'current_password' => $currentPassword,
        'new_password' => $newPassword = fake()->password(8, 10),
        'new_password_confirmation' => $newPassword,
    ])
        ->assertRedirect(route('shop.customers.account.profile.index'));

    Mail::assertQueued(UpdatePasswordNotification::class);
});

it('should fail validation when required fields are missing on profile update', function () {
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.profile.update'), [
        'gender' => 'UNKNOWN',
        'date_of_birth' => now()->tomorrow()->toDateString(),
        'email' => 'WRONG_EMAIL',
        'image' => 'INVALID',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('gender')
        ->assertJsonValidationErrorFor('email');
});

// ============================================================================
// Delete Account
// ============================================================================

it('should delete the customer account', function () {
    $customer = Customer::factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.profile.destroy'), [
        'password' => 'admin123',
    ])
        ->assertRedirect(route('shop.customer.session.index'));

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
});

it('should fail validation when password is missing on account delete', function () {
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.profile.destroy'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('password');
});

// ============================================================================
// Reviews
// ============================================================================

it('should return the customer reviews page', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    $review = ProductReview::factory()->create([
        'product_id' => $product->id,
        'customer_id' => $customer->id,
    ]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.reviews.index'))
        ->assertOk()
        ->assertSeeText($review->title);
});

// ============================================================================
// Addresses
// ============================================================================

it('should return the addresses page', function () {
    $customer = Customer::factory()->create();

    CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.addresses.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.addresses.index.add-address'));
});

it('should return the address create page', function () {
    $this->loginAsCustomer();

    get(route('shop.customers.account.addresses.create'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.addresses.index.add-address'));
});

it('should store a customer address', function () {
    $customer = $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'), [
        'customer_id' => $customer->id,
        'company_name' => fake()->company(),
        'first_name' => $firstName = fake()->firstName(),
        'last_name' => $lastName = fake()->lastName(),
        'address' => [fake()->streetAddress()],
        'country' => fake()->countryCode(),
        'state' => fake()->state(),
        'city' => fake()->city(),
        'postcode' => rand(11111, 99999),
        'phone' => fake()->e164PhoneNumber(),
        'email' => fake()->safeEmail(),
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ])
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertDatabaseHas('addresses', [
        'customer_id' => $customer->id,
        'first_name' => $firstName,
        'last_name' => $lastName,
    ]);
});

it('should fail validation when required fields are missing on address store', function () {
    $this->loginAsCustomer();

    postJson(route('shop.customers.account.addresses.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('address')
        ->assertJsonValidationErrorFor('city')
        ->assertJsonValidationErrorFor('phone')
        ->assertJsonValidationErrorFor('country');
});

it('should return the address edit page', function () {
    $customer = Customer::factory()->create();
    $address = CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.addresses.edit', $address->id))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.addresses.edit.title'));
});

it('should update a customer address', function () {
    $customer = Customer::factory()->create();
    $address = CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    $this->loginAsCustomer($customer);

    putJson(route('shop.customers.account.addresses.update', $address->id), [
        'customer_id' => $customer->id,
        'company_name' => $company = fake()->company(),
        'first_name' => $firstName = fake()->firstName(),
        'last_name' => $lastName = fake()->lastName(),
        'address' => [fake()->streetAddress()],
        'country' => $address->country,
        'state' => $address->state,
        'city' => $address->city,
        'postcode' => rand(11111, 99999),
        'phone' => $address->phone,
        'email' => fake()->safeEmail(),
        'address_type' => $address->address_type,
    ])
        ->assertRedirect(route('shop.customers.account.addresses.index'));

    $this->assertDatabaseHas('addresses', [
        'id' => $address->id,
        'first_name' => $firstName,
        'last_name' => $lastName,
        'company_name' => $company,
    ]);
});

it('should fail validation when required fields are missing on address update', function () {
    $customer = Customer::factory()->create();
    $address = CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    $this->loginAsCustomer($customer);

    putJson(route('shop.customers.account.addresses.update', $address->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name');
});

it('should set a default address', function () {
    $customer = Customer::factory()->create();
    $address = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
        'default_address' => 0,
    ]);

    $this->loginAsCustomer($customer);

    patchJson(route('shop.customers.account.addresses.update.default', $address->id))
        ->assertRedirect();

    $this->assertDatabaseHas('addresses', [
        'id' => $address->id,
        'default_address' => 1,
    ]);
});

it('should delete a customer address', function () {
    $customer = Customer::factory()->create();
    $address = CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    $this->loginAsCustomer($customer);

    deleteJson(route('shop.customers.account.addresses.delete', $address->id))
        ->assertRedirect();

    $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
});

// ============================================================================
// Forgot Password
// ============================================================================

it('should send password reset email', function () {
    Notification::fake();

    $customer = Customer::factory()->create();

    postJson(route('shop.customers.forgot_password.store'), [
        'email' => $customer->email,
    ])
        ->assertRedirect(route('shop.customers.forgot_password.create'));

    $this->assertDatabaseHas('customer_password_resets', [
        'email' => $customer->email,
    ]);

    Notification::assertSentTo($customer, ResetPasswordNotification::class);
});

it('should not send reset email for non-existent email', function () {
    postJson(route('shop.customers.forgot_password.store'), [
        'email' => 'nonexistent@example.com',
    ])
        ->assertRedirect(route('shop.customers.forgot_password.create'));

    $this->assertDatabaseMissing('customer_password_resets', [
        'email' => 'nonexistent@example.com',
    ]);
});

it('should fail validation when email is missing on forgot password', function () {
    postJson(route('shop.customers.forgot_password.store'))
        ->assertJsonValidationErrorFor('email');
});
