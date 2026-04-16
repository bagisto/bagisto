<?php

use Illuminate\Support\Facades\Hash;
use Webkul\Customer\Models\Customer;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

// ============================================================================
// Login Page
// ============================================================================

it('should return the customer login page', function () {
    get(route('shop.customer.session.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.login-form.page-title'));
});

// ============================================================================
// Login
// ============================================================================

it('should login a customer with valid credentials', function () {
    $customer = Customer::factory()->create([
        'password' => Hash::make($password = 'admin123'),
    ]);

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => $password,
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionMissing('error');
});

it('should fail login with invalid email', function () {
    Customer::factory()->create([
        'password' => Hash::make('admin123'),
    ]);

    post(route('shop.customer.session.create'), [
        'email' => 'wrong@email.com',
        'password' => 'admin123',
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionHas('error');
});

it('should fail login with invalid password', function () {
    $customer = Customer::factory()->create();

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'WRONG_PASSWORD',
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionHas('error');
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
