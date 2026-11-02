<?php

use Webkul\Checkout\Facades\Cart;

// ============================================================================
// What The Public Methods Answer Before A Cart Exists
// ============================================================================

it('should hold no cart to begin with', function () {
    expect(Cart::getCart())->toBeNull();
});

it('should find no item for a product', function () {
    expect(Cart::getItemByProduct(['product_id' => 1]))->toBeNull();
});

it('should take a coupon code nowhere rather than fail', function () {
    expect(Cart::setCouponCode('ANYTHING'))->toBeInstanceOf(Webkul\Checkout\Cart::class);
});

it('should remove a coupon code without a cart to remove it from', function () {
    expect(Cart::removeCouponCode())->toBeInstanceOf(Webkul\Checkout\Cart::class);
});

it('should move nothing to the wishlist', function () {
    expect(Cart::moveToWishlist(1))->toBeFalse();
});

it('should measure nothing against the minimum order', function () {
    expect(Cart::getOrderAmount())->toBe(0.0);
});

it('should write no customer details', function () {
    Cart::setCustomerPersonnelDetails();

    expect(Cart::getCart())->toBeNull();
});

it('should find no saved address to have filled a cart address from', function () {
    expect(Cart::customerAddressId(['billing' => ['address_id' => 1]]))->toBeNull();
});

it('should save no addresses without a cart to save them against', function () {
    Cart::saveAddresses(['billing' => ['first_name' => 'Nobody']]);

    expect(Cart::getCart())->toBeNull();
});

it('should refuse to write either cart address without a cart', function (string $method) {
    expect(fn () => Cart::$method(['billing' => ['first_name' => 'Nobody']]))
        ->toThrow(Exception::class, trans('shop::app.checkout.cart.index.empty-product'));
})->with([
    'the billing address' => ['updateOrCreateBillingAddress'],
    'the shipping address' => ['updateOrCreateShippingAddress'],
]);

it('should report the errors of a cart that is not there', function () {
    expect(Cart::getErrors())->toBe([
        'error_code' => 'CART_NOT_FOUND',
        'message' => trans('shop::app.checkout.cart.index.empty-product'),
    ]);
});
