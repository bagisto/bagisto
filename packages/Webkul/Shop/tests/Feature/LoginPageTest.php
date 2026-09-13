<?php

use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

/**
 * Create a customer who can sign in with the given password.
 */
function customerWithPassword(string $password, array $attributes = []): Customer
{
    return Customer::factory()->create(array_merge(['password' => Hash::make($password)], $attributes));
}

// ============================================================================
// Login Page
// ============================================================================

it('should return the customer login page', function () {
    get(route('shop.customer.session.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.login-form.page-title'));
});

it('should send a signed-in customer from the login page to the home page', function () {
    $this->loginAsCustomer();

    get(route('shop.customer.session.index'))
        ->assertRedirect(route('shop.home.index'));
});

// ============================================================================
// Login
// ============================================================================

it('should login a customer with valid credentials', function () {
    $customer = customerWithPassword('admin123');

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'admin123',
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionMissing('error');

    $this->assertAuthenticatedAs($customer, 'customer');
});

it('should land on the account page after login when the store is set up that way', function () {
    $this->setConfig('customer.settings.login_options.redirected_to_page', 'account');

    $customer = customerWithPassword('admin123');

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'admin123',
    ])
        ->assertRedirectToRoute('shop.customers.account.profile.index');
});

it('should prefer the page the customer was turned away from over the configured landing page', function () {
    $this->setConfig('customer.settings.login_options.redirected_to_page', 'account');

    $customer = customerWithPassword('admin123');

    $this->withSession(['shop.url.intended' => route('shop.checkout.onepage.index')])
        ->post(route('shop.customer.session.create'), [
            'email' => $customer->email,
            'password' => 'admin123',
        ])
        ->assertRedirect(route('shop.checkout.onepage.index'))
        ->assertSessionMissing('shop.url.intended');
});

it('should fail login with invalid email', function () {
    customerWithPassword('admin123');

    post(route('shop.customer.session.create'), [
        'email' => 'wrong@email.com',
        'password' => 'admin123',
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionHas('error', trans('shop::app.customers.login-form.invalid-credentials'));

    $this->assertGuest('customer');
});

it('should fail login with invalid password', function () {
    $customer = customerWithPassword('admin123');

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'WRONG_PASSWORD',
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionHas('error', trans('shop::app.customers.login-form.invalid-credentials'));

    $this->assertGuest('customer');
});

it('should not sign in a customer whose account is deactivated', function () {
    $customer = customerWithPassword('admin123', ['status' => 0]);

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'admin123',
    ])
        ->assertRedirect()
        ->assertSessionHas('warning', trans('shop::app.customers.login-form.not-activated'));

    $this->assertGuest('customer');
});

it('should not sign in a customer who has not verified their email yet', function () {
    $customer = customerWithPassword('admin123', ['is_verified' => 0]);

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'admin123',
    ])
        ->assertRedirect()
        ->assertSessionHas('info', trans('shop::app.customers.login-form.verify-first'))
        ->assertCookie('enable-resend', 'true');

    $this->assertGuest('customer');
});

it('should fail validation when credentials are missing', function () {
    postJson(route('shop.customer.session.create'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password');
});

it('should fail validation with invalid email format', function () {
    postJson(route('shop.customer.session.create'), [
        'email' => 'not-an-email',
        'password' => 'admin123',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('email');
});

it('should fail validation when password is too short', function () {
    postJson(route('shop.customer.session.create'), [
        'email' => fake()->email(),
        'password' => 'shop',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('password');
});

// ============================================================================
// Logout
// ============================================================================

it('should sign the customer out and destroy the whole session', function () {
    $this->loginAsCustomer();

    $this->withSession(['probe' => 'still-here'])
        ->delete(route('shop.customer.session.destroy'))
        ->assertRedirect(route('shop.home.index'))
        ->assertSessionMissing('probe');

    $this->assertGuest('customer');
});
