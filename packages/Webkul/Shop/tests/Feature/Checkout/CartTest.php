<?php

use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

// ============================================================================
// Cart Display
// ============================================================================

it('should display the cart items for a guest user', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id)->assertOk();

    get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 1)
        ->assertJsonPath('data.is_guest', true);
});

it('should display the cart items for a customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id)->assertOk();

    get(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.is_guest', false);
});

// ============================================================================
// Remove Cart Item — Validation
// ============================================================================

it('should fail validation when cart item id is not provided on remove for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    deleteJson(route('shop.api.checkout.cart.destroy'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('cart_item_id');
});

it('should fail validation when cart item id is not provided on remove for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    deleteJson(route('shop.api.checkout.cart.destroy'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('cart_item_id');
});

it('should fail validation when wrong cart item id is provided on remove for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    deleteJson(route('shop.api.checkout.cart.destroy'), ['cart_item_id' => 99999])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('cart_item_id');
});

// ============================================================================
// Remove Cart Item
// ============================================================================

it('should remove a product from the guest cart', function () {
    $product = $this->createSimpleProduct();

    $response = $this->addProductToCart($product->id);
    $cartItemId = $response->json('data.items.0.id');

    $this->removeCartItem($cartItemId)->assertOk();

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
});

it('should remove a product from the customer cart', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $response = $this->addProductToCart($product->id);
    $cartItemId = $response->json('data.items.0.id');

    $this->removeCartItem($cartItemId)->assertOk();

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItemId]);
});

it('should remove only one product when cart contains two products for guest', function () {
    $productA = $this->createSimpleProduct();
    $productB = $this->createSimpleProduct();

    $this->addProductToCart($productA->id);
    $responseB = $this->addProductToCart($productB->id);

    $cartItemBId = $responseB->json('data.items.1.id') ?? $responseB->json('data.items.0.id');

    $this->removeCartItem($cartItemBId)->assertOk();

    $this->assertDatabaseHas('cart_items', ['product_id' => $productA->id]);
});

// ============================================================================
// Remove All Cart Items
// ============================================================================

it('should remove selected products from the guest cart', function () {
    $productA = $this->createSimpleProduct();
    $productB = $this->createSimpleProduct();

    $this->addProductToCart($productA->id);
    $response = $this->addProductToCart($productB->id);

    $itemIds = collect($response->json('data.items'))->pluck('id')->toArray();

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), [
        'ids' => $itemIds,
    ])
        ->assertOk();
});

it('should remove selected products from the customer cart', function () {
    $productA = $this->createSimpleProduct();
    $productB = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($productA->id);
    $response = $this->addProductToCart($productB->id);

    $itemIds = collect($response->json('data.items'))->pluck('id')->toArray();

    deleteJson(route('shop.api.checkout.cart.destroy_selected'), [
        'ids' => $itemIds,
    ])
        ->assertOk();
});

// ============================================================================
// Update Cart Quantities
// ============================================================================

it('should update cart quantities for a guest user', function () {
    $product = $this->createSimpleProduct();

    $response = $this->addProductToCart($product->id);
    $cartItemId = $response->json('data.items.0.id');

    $this->updateCartItem($cartItemId, 3)->assertOk();

    $this->assertDatabaseHas('cart_items', ['id' => $cartItemId, 'quantity' => 3]);
});

it('should update cart quantities for a customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $response = $this->addProductToCart($product->id);
    $cartItemId = $response->json('data.items.0.id');

    $this->updateCartItem($cartItemId, 5)->assertOk();

    $this->assertDatabaseHas('cart_items', ['id' => $cartItemId, 'quantity' => 5]);
});

// ============================================================================
// Add Simple Product
// ============================================================================

it('should fail validation when product id is not provided on add to cart', function () {
    postJson(route('shop.api.checkout.cart.store'), ['quantity' => 1])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('product_id');
});

it('should add a simple product to the cart for a guest user', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.item-add-to-cart'))
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items.0.type', 'simple');
});

it('should add a simple product to the cart for a customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.is_guest', false);
});

// ============================================================================
// Add Virtual Product
// ============================================================================

it('should add a virtual product to the cart for a guest user', function () {
    $product = $this->createVirtualProduct();

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items.0.type', 'virtual');
});

it('should add a virtual product to the cart for a customer', function () {
    $product = $this->createVirtualProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items.0.type', 'virtual');
});

// ============================================================================
// Tax Calculation
// ============================================================================

it('should calculate including tax when adding a product to the cart', function () {
    $taxRate = TaxRate::factory()->create(['tax_rate' => 10]);

    $taxCategory = TaxCategory::factory()->create();
    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id' => $taxRate->id,
    ]);

    $product = $this->createSimpleProduct([
        'tax_category_id' => ['integer_value' => $taxCategory->id, 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->addProductToCart($product->id)
        ->assertOk()
        ->assertJsonPath('data.items_count', 1);
});
