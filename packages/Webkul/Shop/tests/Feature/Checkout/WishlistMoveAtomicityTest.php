<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Wishlist;

/**
 * Make removing the cart item fail, the way a database error or a throwing observer would, once
 * the wishlist row has already been written.
 */
function failOnCartItemRemoval(): void
{
    CartItem::deleting(function () {
        throw new RuntimeException('cart item refused to go');
    });
}

// ============================================================================
// Moving A Cart Item To The Wishlist
// ============================================================================

it('should leave the product in neither place twice when the cart item cannot be removed', function () {
    $product = $this->createSimpleProduct();

    $customer = $this->loginAsCustomer();

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    failOnCartItemRemoval();

    expect(fn () => Cart::moveToWishlist($cartItemId))->toThrow(RuntimeException::class);

    expect(Wishlist::query()->where('customer_id', $customer->id)->where('product_id', $product->id)->count())
        ->toBe(0)
        ->and(CartItem::query()->where('id', $cartItemId)->count())->toBe(1);
});

it('should write the wishlist row and drop the cart item together', function () {
    $product = $this->createSimpleProduct();

    $customer = $this->loginAsCustomer();

    $cartItemId = $this->addProductToCart($product->id)->json('data.items.0.id');

    Cart::moveToWishlist($cartItemId);

    expect(Wishlist::query()->where('customer_id', $customer->id)->where('product_id', $product->id)->count())
        ->toBe(1)
        ->and(CartItem::query()->where('id', $cartItemId)->count())->toBe(0);
});
