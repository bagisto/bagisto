<?php

use Webkul\Checkout\Models\CartItem;

use function Pest\Laravel\putJson;

// ============================================================================
// Updating A Quantity
// ============================================================================

it('should refuse a decimal quantity rather than storing it rounded', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $this->addProductToCart($product->id);

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => 2.5]])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('qty.'.$item->id);

    expect($item->refresh()->quantity)->toBe(1);
});

it('should refuse a quantity that is not a number at all', function (mixed $quantity) {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

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

    $this->addProductToCart($product->id);

    $item = CartItem::latest('id')->first();

    $response = putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => 3]])
        ->assertOk();

    expect($item->refresh()->quantity)->toBe(3)
        ->and((float) $response->json('data.sub_total'))->toBe(300.0);
});

it('should take the cart being updated with no quantity changed, as the cart page posts it', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => []])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.index.quantity-update'));

    expect($item->refresh()->quantity)->toBe(1);
});

it('should still remove the item when the quantity is set to zero', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => 0]]);

    expect(CartItem::find($item->id))->toBeNull();
});

// ============================================================================
// Quantity By Product Type
// ============================================================================

it('should hold a downloadable product at one however many are added', function () {
    $product = $this->createDownloadableProduct();

    $this->addProductToCart($product->id, 5, [
        'links' => $product->downloadable_links->pluck('id')->all(),
    ])->assertOk();

    $item = CartItem::latest('id')->first();

    expect($item->quantity)->toBe(1)
        ->and($item->base_total)->toEqual($item->base_price);
});

it('should hold a downloadable product at one when it is added twice', function () {
    $product = $this->createDownloadableProduct();

    $links = $product->downloadable_links->pluck('id')->all();

    $this->addProductToCart($product->id, 1, ['links' => $links])->assertOk();
    $this->addProductToCart($product->id, 1, ['links' => $links])->assertOk();

    $items = CartItem::where('product_id', $product->id)->get();

    expect($items)->toHaveCount(1)
        ->and($items->first()->quantity)->toBe(1)
        ->and($items->first()->base_total)->toEqual($items->first()->base_price);
});

it('should still add up the quantity when a simple product is added twice', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $this->addProductToCart($product->id)->assertOk();
    $this->addProductToCart($product->id)->assertOk();

    expect(CartItem::latest('id')->first()->quantity)->toBe(2);
});

it('should hold a downloadable product at one when its quantity is updated', function () {
    $product = $this->createDownloadableProduct();

    $this->addProductToCart($product->id, 1, [
        'links' => $product->downloadable_links->pluck('id')->all(),
    ])->assertOk();

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => 5]])
        ->assertOk();

    expect($item->refresh()->quantity)->toBe(1)
        ->and($item->base_total)->toEqual($item->base_price);
});

it('should still let a simple product change quantity', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $this->addProductToCart($product->id);

    $item = CartItem::latest('id')->first();

    putJson(route('shop.api.checkout.cart.update'), ['qty' => [$item->id => 4]])
        ->assertOk();

    expect($item->refresh()->quantity)->toBe(4);
});
