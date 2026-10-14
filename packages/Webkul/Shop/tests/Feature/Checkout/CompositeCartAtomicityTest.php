<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartItem;

/**
 * Make the first child item of a composite product fail to insert, the way a database error or a
 * throwing observer would, leaving the parent already written.
 */
function failOnFirstChildCartItem(): void
{
    CartItem::creating(function (CartItem $cartItem) {
        if ($cartItem->parent_id) {
            throw new RuntimeException('child item refused');
        }
    });
}

// ============================================================================
// Adding A Composite Product
// ============================================================================

it('should leave no cart item behind when a composite child cannot be written', function () {
    $product = $this->createBundleProduct();

    $options = $product->bundle_options->first();

    failOnFirstChildCartItem();

    $add = fn () => Cart::addProduct($product, [
        'product_id' => $product->id,
        'quantity' => 1,
        'bundle_options' => [
            $options->id => [$options->bundle_option_products->first()->id],
        ],
    ]);

    expect($add)->toThrow(RuntimeException::class);

    expect(CartItem::query()->where('product_id', $product->id)->count())->toBe(0);
});

it('should write the parent and its children together when nothing fails', function () {
    $product = $this->createBundleProduct();

    $options = $product->bundle_options->first();

    Cart::addProduct($product, [
        'product_id' => $product->id,
        'quantity' => 1,
        'bundle_options' => [
            $options->id => [$options->bundle_option_products->first()->id],
        ],
    ]);

    $parent = CartItem::query()->where('product_id', $product->id)->firstOrFail();

    expect($parent->children()->count())->toBeGreaterThan(0);
});
