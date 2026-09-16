<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Wishlist;

use function Pest\Laravel\postJson;

// ============================================================================
// Request Validation
// ============================================================================

it('should refuse to move a wishlist item to the cart with a negative quantity', function () {
    $product = $this->createSimpleProduct();

    $customer = Customer::factory()->create();

    $wishlistItem = Wishlist::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'product_id' => $product->id,
        'customer_id' => $customer->id,
    ]);

    $this->loginAsCustomer($customer);

    postJson(route('shop.api.customers.account.wishlist.move_to_cart', $wishlistItem->id), [
        'quantity' => -3,
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('quantity');

    $this->assertDatabaseHas('wishlist_items', ['id' => $wishlistItem->id]);

    $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
});

it('should refuse a negative quantity for a grouped or bundle option when adding to the cart', function () {
    $product = $this->createSimpleProduct();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'qty' => [$product->id => -3],
        'bundle_option_qty' => [1 => -3],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('qty.'.$product->id)
        ->assertJsonValidationErrorFor('bundle_option_qty.1');

    $this->assertDatabaseMissing('cart_items', ['product_id' => $product->id]);
});

it('should refuse a negative quantity when moving cart items to the wishlist', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $cartItemId = $this->addProductToCart($product->id)->assertOk()->json('data.items.0.id');

    postJson(route('shop.api.checkout.cart.move_to_wishlist'), [
        'ids' => [$cartItemId],
        'qty' => [-3],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('qty.0');

    $this->assertDatabaseHas('cart_items', ['id' => $cartItemId]);
});

// ============================================================================
// Cart
// ============================================================================

it('should never add less than one unit, even when a negative quantity reaches the cart', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $item = Cart::addProduct($product, [
        'product_id' => $product->id,
        'quantity' => -3,
    ])->items->first();

    expect($item->quantity)->toBe(1)
        ->and((float) $item->base_total)->toBePrice(100);
});

it('should only add the positive quantities of a grouped product\'s own children to the cart', function () {
    $product = $this->createGroupedProduct();

    $children = $product->grouped_products()->pluck('associated_product_id');

    $unrelated = $this->createSimpleProduct();

    $cart = Cart::addProduct($product, [
        'product_id' => $product->id,
        'qty' => [
            $children[0] => -3,
            $children[1] => 1,
            $unrelated->id => 2,
        ],
    ]);

    expect($cart->items->pluck('product_id')->all())->toBe([$children[1]])
        ->and($cart->items->first()->quantity)->toBe(1);
});
