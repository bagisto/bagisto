<?php

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Facades\Cart;
use Webkul\Customer\Models\Customer;

/**
 * Browse as a signed-in customer, which the wishlist needs and Shop's own bench provides
 * through a concern this package does not depend on.
 */
function actAsCustomer(): Customer
{
    $customer = Customer::factory()->create();

    test()->actingAs($customer);

    return $customer;
}

/**
 * Record the cart events fired from here on, in order, without faking the ones the cart's own
 * listeners need — a blanket fake silences CartRule and the totals come out wrong.
 */
function recordCartEvents(array $names): ArrayObject
{
    $fired = new ArrayObject;

    foreach ($names as $name) {
        Event::listen($name, function () use ($name, $fired) {
            $fired[] = $name;
        });
    }

    return $fired;
}

// ============================================================================
// Adding And Removing Announce Themselves
// ============================================================================

it('should announce a product going into the cart before and after it is written', function () {
    $product = $this->createSimpleProduct();

    $fired = recordCartEvents(['checkout.cart.add.before', 'checkout.cart.add.after']);

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    expect($fired->getArrayCopy())->toBe(['checkout.cart.add.before', 'checkout.cart.add.after']);
});

it('should announce a cart item being removed before and after it goes', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $itemId = Cart::getCart()->items->first()->id;

    $fired = recordCartEvents(['checkout.cart.delete.before', 'checkout.cart.delete.after']);

    Cart::removeItem($itemId);

    expect($fired->getArrayCopy())->toBe(['checkout.cart.delete.before', 'checkout.cart.delete.after']);
});

it('should announce each item it updates, once per item', function () {
    $first = $this->createSimpleProduct();

    $second = $this->createSimpleProduct();

    Cart::addProduct($first, ['product_id' => $first->id, 'quantity' => 1]);

    Cart::addProduct($second, ['product_id' => $second->id, 'quantity' => 1]);

    $items = Cart::getCart()->items;

    $fired = recordCartEvents(['checkout.cart.update.before', 'checkout.cart.update.after']);

    Cart::updateItems(['qty' => [$items->first()->id => 2, $items->last()->id => 3]]);

    expect($fired->getArrayCopy())->toBe([
        'checkout.cart.update.before',
        'checkout.cart.update.after',
        'checkout.cart.update.before',
        'checkout.cart.update.after',
    ]);
});

// ============================================================================
// Collecting Totals Announces Its Phases In Order
// ============================================================================

it('should announce the totals collection around the tax it calculates', function () {
    $product = $this->createSimpleProduct();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $fired = recordCartEvents([
        'checkout.cart.collect.totals.before',
        'checkout.cart.calculate.items.tax.before',
        'checkout.cart.calculate.items.tax.after',
        'checkout.cart.collect.totals.after',
    ]);

    Cart::collectTotals();

    expect($fired->getArrayCopy())->toBe([
        'checkout.cart.collect.totals.before',
        'checkout.cart.calculate.items.tax.before',
        'checkout.cart.calculate.items.tax.after',
        'checkout.cart.collect.totals.after',
    ]);
});

// ============================================================================
// Moving Between The Cart And The Wishlist Announces Itself
// ============================================================================

it('should announce a wishlist row being created when a cart item moves out', function () {
    $product = $this->createSimpleProduct();

    actAsCustomer();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $fired = recordCartEvents(['customer.wishlist.create.before', 'customer.wishlist.create.after']);

    Cart::moveToWishlist(Cart::getCart()->items->first()->id);

    expect($fired->getArrayCopy())->toBe(['customer.wishlist.create.before', 'customer.wishlist.create.after']);
});

it('should announce the cart line going away when an item moves to the wishlist', function () {
    $product = $this->createSimpleProduct();

    actAsCustomer();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $fired = recordCartEvents(['checkout.cart.delete.before', 'checkout.cart.delete.after']);

    Cart::moveToWishlist(Cart::getCart()->items->first()->id);

    expect($fired->getArrayCopy())->toBe(['checkout.cart.delete.before', 'checkout.cart.delete.after']);
});

it('should announce a wishlist row being updated when the product is already on it', function () {
    $product = $this->createSimpleProduct();

    actAsCustomer();

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    Cart::moveToWishlist(Cart::getCart()->items->first()->id);

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    $fired = recordCartEvents(['customer.wishlist.update.before', 'customer.wishlist.update.after']);

    Cart::moveToWishlist(Cart::getCart()->items->first()->id);

    expect($fired->getArrayCopy())->toBe(['customer.wishlist.update.before', 'customer.wishlist.update.after']);
});
