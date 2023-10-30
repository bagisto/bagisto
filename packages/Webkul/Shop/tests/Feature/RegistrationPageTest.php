<?php

use Webkul\Customer\Models\Customer;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    Customer::query()->delete();
});

it('returns a successful response', function () {
    // Act & Assert
    get(route('shop.customers.register.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.signup-form.page-title'));
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
