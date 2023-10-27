<?php

use Illuminate\Support\Str;
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

it('displays the current currency code and channel code', function () {
    // Act
    $resonse = get(route('shop.home.index'));

    // Assert
    $resonse->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($resonse->content(), core()->getCurrentChannelCode()))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), core()->getCurrentCurrencyCode()))
        ->toBeTruthy();
});

it('displays the "Sign In" and "Sign Up" buttons when the customer is not logged in', function () {
    // Act
    $resonse = get(route('shop.home.index'));

    // Assert
    $resonse->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.sign-in')))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.sign-up')))
        ->toBeTruthy();
});

it('displays navigation buttons when the customer is logged in', function () {
    // Act
    $this->loginAsCustomer();

    $resonse = get(route('shop.home.index'));

    // Assert
    $resonse->assertOk();

    /**
     * We avoid using the `assertSeeText` method of the response because it may sometimes
     * produce false positive results when dealing with large DOM sizes.
     */
    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.profile')))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.orders')))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.wishlist')))
        ->toBeTruthy();

    expect(Str::contains($resonse->content(), trans('shop::app.components.layouts.header.logout')))
        ->toBeTruthy();
});
