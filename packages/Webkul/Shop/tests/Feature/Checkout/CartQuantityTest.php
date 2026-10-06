<?php

use Webkul\Checkout\Models\CartItem;

use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

// ============================================================================
// Updating A Quantity
// ============================================================================

it('should refuse a decimal quantity rather than storing it rounded', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    postJson(route('shop.api.checkout.cart.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => 2.5]])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('qty.'.$item->id);

    expect($item->refresh()->quantity)->toBe(1);
});

it('should refuse a quantity that is not a number at all', function (mixed $quantity) {
    $product = $this->createSimpleProduct();

    postJson(route('shop.api.checkout.cart.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => $quantity]])
        ->assertUnprocessable();

    expect($item->refresh()->quantity)->toBe(1);
})->with([
    'text' => 'two',
    'negative' => -1,
    'empty' => '',
]);

it('should still take a whole quantity', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    postJson(route('shop.api.checkout.cart.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $item = CartItem::latest('id')->first();

    $response = putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => 3]])
        ->assertOk();

    expect($item->refresh()->quantity)->toBe(3)
        ->and((float) $response->json('data.sub_total'))->toBe(300.0);
});

it('should take the cart being updated with no quantity changed, as the cart page posts it', function () {
    $product = $this->createSimpleProduct();

    postJson(route('shop.api.checkout.cart.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => []])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.quantity-update'));

    expect($item->refresh()->quantity)->toBe(1);
});

it('should still remove the item when the quantity is set to zero', function () {
    $product = $this->createSimpleProduct();

    postJson(route('shop.api.checkout.cart.store'), ['product_id' => $product->id, 'quantity' => 1]);

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => 0]]);

    expect(CartItem::find($item->id))->toBeNull();
});
