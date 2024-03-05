<?php

use Illuminate\Support\Facades\Hash;
use Webkul\Faker\Helpers\Customer as CustomerFaker;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

it('returns a successful response', function () {
    // Act & Assert
    get(route('shop.customer.session.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.login-form.page-title'));
});

it('should fails validation errors when email and password not provided when login', function () {
    // Act & Assert
    postJson(route('shop.customer.session.create'))
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('should fails validation errors when email is not valid', function () {
    // Act & Assert
    postJson(route('shop.customer.session.create'), [
        'email'    => fake()->word(),
        'password' => 'shop123',
    ])
        ->assertJsonValidationErrorFor('email')
        ->assertUnprocessable();
});

it('should fails validation errors when password length not valid', function () {
    // Act & Assert
    postJson(route('shop.customer.session.create'), [
        'email'    => fake()->email(),
        'password' => 'shop',
    ])
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('successfully logins a customer', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create([
        'password' => Hash::make($password = 'admin123'),
    ]);

    // Act & Assert
    post(route('shop.customer.session.create'), [
        'email'    => $customer->email,
        'password' => $password,
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionMissing('error')
        ->assertSessionMissing('warning')
        ->assertSessionMissing('info');
});

it('fails to log in a customer if the email is invalid', function () {
    // Arrange
    (new CustomerFaker())->factory()->create([
        'password' => Hash::make($password = 'admin123'),
    ]);

    // Act & Assert
    post(route('shop.customer.session.create'), [
        'email'    => 'wrong@email.com',
        'password' => $password,
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionHas('error');
});

it('fails to log in a customer if the password is invalid', function () {
    // Arrange
    $customer = (new CustomerFaker())->factory()->create();

    // Act & Assert
    post(route('shop.customer.session.create'), [
        'email'    => $customer->email,
        'password' => 'WRONG_PASSWORD',
    ])
        ->assertRedirectToRoute('shop.home.index')
        ->assertSessionHas('error');
});
