<?php

use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Wishlist;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * Put a product on the wishlist of the given customer on the current channel.
 */
function wishlistItemFor(Customer $customer, int $productId): Wishlist
{
    return Wishlist::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'product_id' => $productId,
        'customer_id' => $customer->id,
    ]);
}

// ============================================================================
// Index
// ============================================================================

it('should return the wishlist page', function () {
    $product = $this->createSimpleProduct();

    $customer = Customer::factory()->create();

    wishlistItemFor($customer, $product->id);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.wishlist.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.wishlist.page-title'));
});

it('should list the wishlisted items of the customer via the api', function () {
    $product = $this->createSimpleProduct();

    $customer = Customer::factory()->create();

    wishlistItemFor($customer, $product->id);

    wishlistItemFor(Customer::factory()->create(), $product->id);

    $this->loginAsCustomer($customer);

    getJson(route('shop.api.customers.account.wishlist.index'))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.product.id', $product->id);
});

// ============================================================================
// Store
// ============================================================================

it('should fail validation when product id is missing on wishlist store', function () {
    $this->loginAsCustomer();

    postJson(route('shop.api.customers.account.wishlist.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('product_id');
});

it('should add a product to the wishlist', function () {
    $product = $this->createSimpleProduct();

    $customer = $this->loginAsCustomer();

    postJson(route('shop.api.customers.account.wishlist.store'), [
        'product_id' => $product->id,
    ])
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.customers.account.wishlist.success'));

    $this->assertDatabaseHas('wishlist_items', [
        'customer_id' => $customer->id,
        'product_id' => $product->id,
        'channel_id' => core()->getCurrentChannel()->id,
    ]);
});

it('should take a product off the wishlist when it is added a second time', function () {
    $product = $this->createSimpleProduct();

    $customer = $this->loginAsCustomer();

    postJson(route('shop.api.customers.account.wishlist.store'), ['product_id' => $product->id])->assertOk();

    postJson(route('shop.api.customers.account.wishlist.store'), ['product_id' => $product->id])
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.customers.account.wishlist.removed'));

    $this->assertDatabaseMissing('wishlist_items', [
        'customer_id' => $customer->id,
        'product_id' => $product->id,
    ]);
});

// ============================================================================
// Remove
// ============================================================================

it('should remove a product from the wishlist', function () {
    $product = $this->createSimpleProduct();

    $customer = Customer::factory()->create();

    $wishlistItem = wishlistItemFor($customer, $product->id);

    $this->loginAsCustomer($customer);

    deleteJson(route('shop.api.customers.account.wishlist.destroy', $wishlistItem->id))
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.customers.account.wishlist.removed'));

    $this->assertDatabaseMissing('wishlist_items', ['id' => $wishlistItem->id]);
});

it('should not remove the wishlist item of another customer', function () {
    $wishlistItem = wishlistItemFor(Customer::factory()->create(), $this->createSimpleProduct()->id);

    $this->loginAsCustomer();

    deleteJson(route('shop.api.customers.account.wishlist.destroy', $wishlistItem->id))
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.customers.account.wishlist.remove-fail'));

    $this->assertDatabaseHas('wishlist_items', ['id' => $wishlistItem->id]);
});

it('should remove all products from the wishlist', function () {
    $customer = Customer::factory()->create();

    foreach (range(1, 2) as $index) {
        wishlistItemFor($customer, $this->createSimpleProduct()->id);
    }

    $this->loginAsCustomer($customer);

    deleteJson(route('shop.api.customers.account.wishlist.destroy_all'))
        ->assertOk()
        ->assertJsonPath('data.message', trans('shop::app.customers.account.wishlist.removed'));

    $this->assertDatabaseMissing('wishlist_items', ['customer_id' => $customer->id]);
});

// ============================================================================
// Move To Cart
// ============================================================================

it('should move a wishlist item to the cart', function () {
    $product = $this->createSimpleProduct();

    $customer = Customer::factory()->create();

    $wishlistItem = wishlistItemFor($customer, $product->id);

    $this->loginAsCustomer($customer);

    postJson(route('shop.api.customers.account.wishlist.move_to_cart', $wishlistItem->id), [
        'quantity' => 2,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.customers.account.wishlist.moved-success'));

    $this->assertDatabaseMissing('wishlist_items', ['id' => $wishlistItem->id]);

    $this->assertCartHasProduct($product->id, 2);
});

it('should not move the wishlist item of another customer to the cart', function () {
    $product = $this->createSimpleProduct();

    $wishlistItem = wishlistItemFor(Customer::factory()->create(), $product->id);

    $this->loginAsCustomer();

    postJson(route('shop.api.customers.account.wishlist.move_to_cart', $wishlistItem->id), [
        'quantity' => 1,
    ])
        ->assertNotFound();

    $this->assertDatabaseHas('wishlist_items', ['id' => $wishlistItem->id]);

    $this->assertCartIsEmpty();
});
