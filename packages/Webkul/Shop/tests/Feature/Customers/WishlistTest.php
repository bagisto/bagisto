<?php

use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\Wishlist;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Index
// ============================================================================

it('should return the wishlist page', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    Wishlist::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'product_id' => $product->id,
        'customer_id' => $customer->id,
    ]);

    $this->loginAsCustomer($customer);

    get(route('shop.customers.account.wishlist.index'))
        ->assertOk()
        ->assertSeeText(trans('shop::app.customers.account.wishlist.page-title'));
});

// ============================================================================
// API
// ============================================================================

it('should return all wishlisted items via API', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    Wishlist::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'product_id' => $product->id,
        'customer_id' => $customer->id,
    ]);

    $this->loginAsCustomer($customer);

    $this->getJson(route('shop.api.customers.account.wishlist.index'))
        ->assertOk()
        ->assertJsonStructure(['data']);
});

it('should fail validation when product id is missing on wishlist store', function () {
    $this->loginAsCustomer();

    postJson(route('shop.api.customers.account.wishlist.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('product_id');
});

it('should add a product to the wishlist', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    postJson(route('shop.api.customers.account.wishlist.store'), [
        'product_id' => $product->id,
    ])
        ->assertOk();
});

it('should remove a product from the wishlist', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    $wishlistItem = Wishlist::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'product_id' => $product->id,
        'customer_id' => $customer->id,
    ]);

    $this->loginAsCustomer($customer);

    deleteJson(route('shop.api.customers.account.wishlist.destroy', $wishlistItem->id))
        ->assertOk();

    $this->assertDatabaseMissing('wishlist_items', ['id' => $wishlistItem->id]);
});

it('should remove all products from the wishlist', function () {
    $customer = Customer::factory()->create();

    $products = collect([
        $this->createSimpleProduct(),
        $this->createSimpleProduct(),
    ]);

    foreach ($products as $product) {
        Wishlist::factory()->create([
            'channel_id' => core()->getCurrentChannel()->id,
            'product_id' => $product->id,
            'customer_id' => $customer->id,
        ]);
    }

    $this->loginAsCustomer($customer);

    deleteJson(route('shop.api.customers.account.wishlist.destroy_all'))
        ->assertOk();

    $this->assertDatabaseMissing('wishlist_items', ['customer_id' => $customer->id]);
});

it('should move a wishlist item to the cart', function () {
    $product = $this->createSimpleProduct();
    $customer = Customer::factory()->create();

    $wishlistItem = Wishlist::factory()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'product_id' => $product->id,
        'customer_id' => $customer->id,
    ]);

    $this->loginAsCustomer($customer);

    postJson(route('shop.api.customers.account.wishlist.move_to_cart', $wishlistItem->id))
        ->assertOk();
});
