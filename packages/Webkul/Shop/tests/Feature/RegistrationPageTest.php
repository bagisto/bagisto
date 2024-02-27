<?php

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

it('returns a successful response', function () {
    // Act & Assert
    get(route('shop.customers.register.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.signup-form.page-title'));
});

it('should fails validation error when certain inputs are invalid when register', function () {
    // Act & Assert
    postJson(route('shop.customers.register.store'))
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('should fails validation error when email is not valid when register', function () {
    // Act & Assert
    postJson(route('shop.customers.register.store'), [
        'email' => 'invalid.email.com',
    ])
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('should fails validation error when password length is not valid when register', function () {
    // Act & Assert
    postJson(route('shop.customers.register.store'), [
        'password' => 'shop',
    ])
        ->assertJsonValidationErrorFor('first_name')
        ->assertJsonValidationErrorFor('last_name')
        ->assertJsonValidationErrorFor('email')
        ->assertJsonValidationErrorFor('password')
        ->assertUnprocessable();
});

it('successfully registers a customer', function () {
    // Arrange
    $requestedCustomer = [
        'first_name'            => fake()->firstName(),
        'last_name'             => fake()->lastName(),
        'email'                 => fake()->email(),
        'password'              => 'admin123',
        'password_confirmation' => 'admin123',
    ];

    // Act & Assert
    post(route('shop.customers.register.store'), $requestedCustomer)
        ->assertRedirectToRoute('shop.customer.session.index')
        ->assertSessionHas('success', trans('shop::app.customers.signup-form.success'));
});
