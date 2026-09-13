<?php

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Webkul\Core\Models\Channel;
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

/**
 * Create a saved address for the given customer.
 */
function addressOf(Customer $customer, array $attributes = []): CustomerAddress
{
    return CustomerAddress::factory()->create(array_merge([
        'customer_id' => $customer->id,
        'default_address' => 0,
    ], $attributes));
}

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

    expect(Hash::check($newPassword, $customer->refresh()->password))->toBeTrue();

    Mail::assertQueued(UpdatePasswordNotification::class, fn (UpdatePasswordNotification $mail) => $mail->hasTo($customer->email));
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

it('should update the profile when the same email exists on another channel', function () {
    $channel = Channel::factory()->create();

    $customer = Customer::factory()->create();

    Customer::factory()->create([
        'email' => $customer->email,
        'channel_id' => $channel->id,
    ]);

    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.profile.update'), [
        'first_name' => $firstName = fake()->firstName(),
        'last_name' => fake()->lastName(),
        'gender' => 'Other',
        'email' => $customer->email,
        'phone' => fake()->e164PhoneNumber(),
    ])
        ->assertRedirect(route('shop.customers.account.profile.index'));

    expect($customer->refresh()->first_name)->toBe($firstName);
});

it('should still refuse an email another customer holds on the same channel', function () {
    $customer = Customer::factory()->create();

    $rival = Customer::factory()->create(['channel_id' => $customer->channel_id]);

    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.profile.update'), [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'gender' => 'Other',
        'email' => $rival->email,
        'phone' => fake()->e164PhoneNumber(),
    ])
        ->assertUnprocessable()
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
        ->assertRedirect(route('shop.customer.session.index'))
        ->assertSessionHas('success', trans('shop::app.customers.account.profile.index.delete-success'));

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
});

it('should refuse to delete the account while an order is still open', function () {
    $customer = Customer::factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    $this->createOrder(customer: $customer);

    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.profile.destroy'), [
        'password' => 'admin123',
    ])
        ->assertRedirect(route('shop.customers.account.profile.index'))
        ->assertSessionHas('error', trans('shop::app.customers.account.profile.index.order-pending'));

    $this->assertDatabaseHas('customers', ['id' => $customer->id]);
});

it('should refuse to delete the account with a wrong password', function () {
    $customer = Customer::factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    $this->loginAsCustomer($customer);

    postJson(route('shop.customers.account.profile.destroy'), [
        'password' => 'not-my-password',
    ])
        ->assertRedirect()
        ->assertSessionHas('error', trans('shop::app.customers.account.profile.index.wrong-password'));

    $this->assertDatabaseHas('customers', ['id' => $customer->id]);
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

    $address = addressOf($customer);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.addresses.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.addresses.index.add-address'))
        ->assertSeeText($address->first_name);
});

it('should return the address create page', function () {
    $this->loginAsCustomer();

    get(route('shop.customers.account.addresses.create'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.addresses.index.add-address'));
});

it('should store a customer address', function () {
    $customer = $this->loginAsCustomer();

    $address = $this->storefrontAddress(['company_name' => fake()->company()]);

    postJson(route('shop.customers.account.addresses.store'), $address)
        ->assertRedirect(route('shop.customers.account.addresses.index'))
        ->assertSessionHas('success', trans('shop::app.customers.account.addresses.index.create-success'));

    $this->assertDatabaseHas('addresses', [
        'customer_id' => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
        'first_name' => $address['first_name'],
        'last_name' => $address['last_name'],
        'company_name' => $address['company_name'],
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

    $address = addressOf($customer);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.addresses.edit', $address->id))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.addresses.edit.title'));
});

it('should not show the address edit page of another customer', function () {
    $address = addressOf(Customer::factory()->create());

    $this->loginAsCustomer();

    get(route('shop.customers.account.addresses.edit', $address->id))
        ->assertNotFound();
});

it('should update a customer address', function () {
    $customer = Customer::factory()->create();

    $address = addressOf($customer);

    $this->loginAsCustomer($customer);

    $payload = $this->storefrontAddress(['company_name' => fake()->company()]);

    putJson(route('shop.customers.account.addresses.update', $address->id), $payload)
        ->assertRedirect(route('shop.customers.account.addresses.index'))
        ->assertSessionHas('success', trans('shop::app.customers.account.addresses.index.edit-success'));

    $this->assertDatabaseHas('addresses', [
        'id' => $address->id,
        'first_name' => $payload['first_name'],
        'last_name' => $payload['last_name'],
        'company_name' => $payload['company_name'],
    ]);
});

it('should not update the address of another customer', function () {
    $address = addressOf(Customer::factory()->create());

    $this->loginAsCustomer();

    putJson(route('shop.customers.account.addresses.update', $address->id), $this->storefrontAddress())
        ->assertRedirect(route('shop.customers.account.addresses.index'))
        ->assertSessionHas('warning', trans('shop::app.customers.account.addresses.index.security-warning'));

    $this->assertDatabaseHas('addresses', [
        'id' => $address->id,
        'first_name' => $address->first_name,
        'customer_id' => $address->customer_id,
    ]);
});

it('should fail validation when required fields are missing on address update', function () {
    $customer = Customer::factory()->create();

    $address = addressOf($customer);

    $this->loginAsCustomer($customer);

    putJson(route('shop.customers.account.addresses.update', $address->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name');
});

it('should make an address the default and unset the previous one', function () {
    $customer = Customer::factory()->create();

    $previousDefault = addressOf($customer, ['default_address' => 1]);

    $address = addressOf($customer);

    $this->loginAsCustomer($customer);

    patchJson(route('shop.customers.account.addresses.update.default', $address->id))
        ->assertRedirect();

    $this->assertDatabaseHas('addresses', ['id' => $address->id, 'default_address' => 1]);

    $this->assertDatabaseHas('addresses', ['id' => $previousDefault->id, 'default_address' => 0]);
});

it('should not make the address of another customer the default', function () {
    $address = addressOf(Customer::factory()->create());

    $this->loginAsCustomer();

    patchJson(route('shop.customers.account.addresses.update.default', $address->id))
        ->assertRedirect();

    $this->assertDatabaseHas('addresses', ['id' => $address->id, 'default_address' => 0]);
});

it('should delete a customer address', function () {
    $customer = Customer::factory()->create();

    $address = addressOf($customer);

    $this->loginAsCustomer($customer);

    deleteJson(route('shop.customers.account.addresses.delete', $address->id))
        ->assertRedirect(route('shop.customers.account.addresses.index'))
        ->assertSessionHas('success', trans('shop::app.customers.account.addresses.index.delete-success'));

    $this->assertDatabaseMissing('addresses', ['id' => $address->id]);
});

it('should not delete the address of another customer', function () {
    $address = addressOf(Customer::factory()->create());

    $this->loginAsCustomer();

    deleteJson(route('shop.customers.account.addresses.delete', $address->id))
        ->assertNotFound();

    $this->assertDatabaseHas('addresses', ['id' => $address->id]);
});

it('should not let the address api update the address of another customer', function () {
    $address = addressOf(Customer::factory()->create());

    $this->loginAsCustomer();

    putJson(route('shop.api.customers.account.addresses.update', $address->id), $this->storefrontAddress([
        'id' => $address->id,
    ]))
        ->assertForbidden();

    $this->assertDatabaseHas('addresses', [
        'id' => $address->id,
        'first_name' => $address->first_name,
    ]);
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
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});
