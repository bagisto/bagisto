<?php

use Webkul\Customer\Models\Customer as CustomerModel;

use function Pest\Laravel\get;

afterEach(function () {
    /**
     * Cleaning up rows which are created.
     */
    CustomerModel::query()->delete();
});

it('returns a successful response', function () {
    // Act & Assert
    get(route('shop.home.index'))
        ->assertOk();
});

it('displays the "Sign In" and "Sign Up" buttons when the customer is not logged in', function () {
    // Act & Assert
    get(route('shop.home.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.components.layouts.header.sign-in'))
        ->assertSeeText(trans('shop::app.components.layouts.header.sign-up'));
});

it('displays navigation buttons when the customer is logged in', function () {
    // Act
    $this->loginAsCustomer();

    // Assert
    get(route('shop.home.index'))
        ->assertOk()
        ->assertSee([
            trans('shop::app.components.layouts.header.profile'),
            trans('shop::app.components.layouts.header.orders'),
            trans('shop::app.components.layouts.header.wishlist'),
            trans('shop::app.components.layouts.header.logout'),
        ]);
});
