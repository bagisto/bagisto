<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

/**
 * Create an admin cart with a customer and a product item.
 */
function createAdminCart($testContext): array
{
    $product = $testContext->createSimpleProduct();
    $customer = Customer::factory()->create();

    CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cart = Cart::factory()->create([
        'customer_id' => $customer->id,
        'customer_first_name' => $customer->first_name,
        'customer_last_name' => $customer->last_name,
        'customer_email' => $customer->email,
        'is_guest' => false,
        'is_active' => 0,
        'items_count' => null,
    ]);

    return ['product' => $product, 'customer' => $customer, 'cart' => $cart];
}

// ============================================================================
// Customer Search
// ============================================================================

it('should search customers via email or name', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.search', ['query' => $customer->first_name]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $customer->id)
        ->assertJsonPath('data.0.email', $customer->email);
});

// ============================================================================
// Order Create Page
// ============================================================================

it('should return the admin order create page', function () {
    $data = createAdminCart($this);

    $this->loginAsAdmin();

    getJson(route('admin.sales.orders.create', $data['cart']->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.create.title', ['name' => $data['customer']->name]));
});

// ============================================================================
// Product Search
// ============================================================================

it('should search products for adding to cart', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    getJson(route('admin.catalog.products.search', ['query' => $product->name]))
        ->assertOk()
        ->assertJsonPath('data.0.id', $product->id)
        ->assertJsonPath('data.0.sku', $product->sku);
});

// ============================================================================
// Cart Item Management
// ============================================================================

it('should add a product to the admin cart', function () {
    $data = createAdminCart($this);

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $data['cart']->id), [
        'product_id' => $data['product']->id,
        'quantity' => $qty = rand(1, 5),
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $data['cart']->id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', $qty);
});

it('should update the cart item quantity', function () {
    $data = createAdminCart($this);

    $this->loginAsAdmin();

    // Add the product first.
    postJson(route('admin.sales.cart.items.store', $data['cart']->id), [
        'product_id' => $data['product']->id,
        'quantity' => 1,
    ]);

    $cartItem = CartItem::where('cart_id', $data['cart']->id)->first();

    putJson(route('admin.sales.cart.items.update', $data['cart']->id), [
        'qty' => [$cartItem->id => $newQty = rand(2, 10)],
    ])
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.create.cart.success-update'));

    $this->assertDatabaseHas('cart_items', [
        'id' => $cartItem->id,
        'quantity' => $newQty,
    ]);
});

it('should remove an item from the admin cart', function () {
    $data = createAdminCart($this);

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $data['cart']->id), [
        'product_id' => $data['product']->id,
        'quantity' => 1,
    ]);

    $cartItem = CartItem::where('cart_id', $data['cart']->id)->first();

    deleteJson(route('admin.sales.cart.items.destroy', $data['cart']->id), [
        'cart_item_id' => $cartItem->id,
    ])
        ->assertOk();

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
});

// ============================================================================
// Address Validation
// ============================================================================

it('should fail validation when addresses are not provided', function () {
    $data = createAdminCart($this);

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $data['cart']->id), [
        'product_id' => $data['product']->id,
        'quantity' => 1,
    ]);

    postJson(route('admin.sales.cart.addresses.store', $data['cart']->id))
        ->assertUnprocessable();
});

// ============================================================================
// Wishlist
// ============================================================================

it('should list wishlist items for a customer', function () {
    $data = createAdminCart($this);

    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.wishlist.items', $data['customer']->id))
        ->assertOk();
});

// ============================================================================
// Recent Orders
// ============================================================================

it('should return the list of recent orders', function () {
    $data = createAdminCart($this);

    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.orders.recent_items', $data['customer']->id))
        ->assertOk();
});
