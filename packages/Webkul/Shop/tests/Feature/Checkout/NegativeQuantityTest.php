<?php

use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Wishlist;
use Webkul\Faker\Helpers\Product as ProductFaker;

use function Pest\Laravel\postJson;

it('should refuse to move a wishlist item to the cart with a negative quantity', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

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
        ->assertJsonValidationErrorFor('quantity');

    expect(cart()->getCart())->toBeNull();
});

it('should refuse a negative quantity for a grouped or bundle option when adding to the cart', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'qty' => [$product->id => -3],
        'bundle_option_qty' => [1 => -3],
    ])
        ->assertJsonValidationErrorFor('qty.'.$product->id)
        ->assertJsonValidationErrorFor('bundle_option_qty.1');
});

it('should refuse a negative quantity when moving cart items to the wishlist', function () {
    $this->loginAsCustomer();

    postJson(route('shop.api.checkout.cart.move_to_wishlist'), [
        'ids' => [1],
        'qty' => [-3],
    ])
        ->assertJsonValidationErrorFor('qty.0');
});

it('should never add less than one unit, even when a negative quantity reaches the cart', function () {
    $product = (new ProductFaker)->getSimpleProductFactory()->create();

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'quantity' => -3,
    ]);

    $item = $cart->items->first();

    expect($item->quantity)->toBe(1);

    expect((float) $item->base_total)->toBeGreaterThan(0);
});

it('should only add the positive quantities of a grouped product\'s own children to the cart', function () {
    $product = (new ProductFaker)->getGroupedProductFactory()->create();

    $children = $product->grouped_products()->pluck('associated_product_id');

    $unrelated = (new ProductFaker)->getSimpleProductFactory()->create();

    $cart = cart()->addProduct($product, [
        'product_id' => $product->id,
        'qty' => [
            $children[0] => -3,
            $children[1] => 1,
            $unrelated->id => 2,
        ],
    ]);

    expect($cart->items->pluck('product_id')->all())->toBe([$children[1]]);

    expect($cart->items->first()->quantity)->toBe(1);
});
