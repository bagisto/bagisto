<?php

use Webkul\Checkout\Models\Cart;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;
use Webkul\Product\Models\Product;
use Webkul\Sales\Models\Order;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

/**
 * Create a customer with a saved address and an empty admin cart for them.
 */
function createAdminCart(): array
{
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

    return ['customer' => $customer, 'cart' => $cart];
}

/**
 * The address payload for the given customer, ready to be posted to the admin cart.
 */
function adminCartAddress(Customer $customer, bool $useForShipping = true): array
{
    $address = CustomerAddress::factory()->create(['customer_id' => $customer->id]);

    return array_merge($address->toArray(), [
        'address' => [fake()->streetAddress()],
        'use_for_shipping' => $useForShipping,
    ]);
}

/**
 * Walk an admin cart through items, address, shipping and payment so an order can be placed from it.
 */
function prepareAdminCartForOrder(Cart $cart, Customer $customer, Product $product, string $paymentMethod = 'cashondelivery'): void
{
    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 2,
    ])->assertOk();

    postJson(route('admin.sales.cart.addresses.store', $cart->id), [
        'billing' => adminCartAddress($customer),
    ])->assertOk();

    if ($product->getTypeInstance()->isStockable()) {
        postJson(route('admin.sales.cart.shipping_methods.store', $cart->id), [
            'shipping_method' => 'free_free',
        ])->assertOk();
    }

    postJson(route('admin.sales.cart.payment_methods.store', $cart->id), [
        'payment' => ['method' => $paymentMethod],
    ])->assertOk();
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
// Cart Creation
// ============================================================================

it('should create an inactive cart for the customer to build the order in', function () {
    $customer = Customer::factory()->create();

    $this->loginAsAdmin();

    $cartId = postJson(route('admin.sales.cart.store'), ['customer_id' => $customer->id])
        ->assertOk()
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.is_guest', false)
        ->json('data.id');

    $this->assertDatabaseHas('cart', [
        'id' => $cartId,
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'is_guest' => false,
        'is_active' => 0,
    ]);
});

// ============================================================================
// Order Create Page
// ============================================================================

it('should return the admin order create page', function () {
    ['customer' => $customer, 'cart' => $cart] = createAdminCart();

    $this->loginAsAdmin();

    getJson(route('admin.sales.orders.create', $cart->id))
        ->assertOk()
        ->assertSeeText(trans('admin::app.sales.orders.create.title', ['name' => $customer->name]));
});

it('should send the admin to the orders listing when the cart does not exist', function () {
    $this->loginAsAdmin();

    getJson(route('admin.sales.orders.create', Cart::query()->max('id') + 1))
        ->assertRedirect(route('admin.sales.orders.index'));
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
    ['cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 3,
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $cart->id)
        ->assertJsonPath('data.items_count', 1)
        ->assertJsonPath('data.items_qty', 3);

    $this->assertDatabaseHas('cart_items', [
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'quantity' => 3,
    ]);
});

it('should update the cart item quantity', function () {
    ['cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $cartItem = CartItem::query()->where('cart_id', $cart->id)->firstOrFail();

    $this->putJson(route('admin.sales.cart.items.update', $cart->id), [
        'qty' => [$cartItem->id => 4],
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.cart.success-update'))
        ->assertJsonPath('data.items_qty', 4);

    $this->assertDatabaseHas('cart_items', [
        'id' => $cartItem->id,
        'quantity' => 4,
    ]);
});

it('should remove an item from the admin cart', function () {
    ['cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $cartItem = CartItem::query()->where('cart_id', $cart->id)->firstOrFail();

    deleteJson(route('admin.sales.cart.items.destroy', $cart->id), [
        'cart_item_id' => $cartItem->id,
    ])
        ->assertOk()
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.cart.success-remove'));

    $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
});

// ============================================================================
// Addresses
// ============================================================================

it('should fail validation when a stockable cart is given no shipping address', function () {
    ['cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    postJson(route('admin.sales.cart.addresses.store', $cart->id))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.address');

    $this->assertDatabaseMissing('addresses', ['cart_id' => $cart->id]);
});

it('should fail validation when the billing address is incomplete', function () {
    ['cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    postJson(route('admin.sales.cart.addresses.store', $cart->id), [
        'billing' => ['first_name' => fake()->firstName(), 'use_for_shipping' => true],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('billing.last_name')
        ->assertJsonValidationErrorFor('billing.email')
        ->assertJsonValidationErrorFor('billing.address')
        ->assertJsonValidationErrorFor('billing.country');
});

it('should copy the billing address to the shipping address and offer the shipping methods', function () {
    ['customer' => $customer, 'cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $address = adminCartAddress($customer);

    postJson(route('admin.sales.cart.addresses.store', $cart->id), ['billing' => $address])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonStructure(['data' => ['shippingMethods']]);

    $this->assertDatabaseHas('addresses', [
        'cart_id' => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        'first_name' => $address['first_name'],
    ]);

    $this->assertDatabaseHas('addresses', [
        'cart_id' => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
        'first_name' => $address['first_name'],
    ]);
});

it('should store only a billing address for a non-stockable cart when use_for_shipping is false', function () {
    ['customer' => $customer, 'cart' => $cart] = createAdminCart();

    $product = $this->createVirtualProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $address = adminCartAddress($customer, useForShipping: false);

    postJson(route('admin.sales.cart.addresses.store', $cart->id), ['billing' => $address])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonStructure(['data' => ['payment_methods']]);

    $this->assertDatabaseHas('addresses', [
        'cart_id' => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        'first_name' => $address['first_name'],
    ]);

    $this->assertDatabaseMissing('addresses', [
        'cart_id' => $cart->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);
});

// ============================================================================
// Place Order
// ============================================================================

it('should place the order from the admin cart and remove the cart', function () {
    ['customer' => $customer, 'cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $this->loginAsAdmin();

    prepareAdminCartForOrder($cart, $customer, $product);

    $response = postJson(route('admin.sales.orders.store', $cart->id))
        ->assertOk()
        ->assertJsonPath('data.redirect', true);

    $order = Order::query()->where('cart_id', $cart->id)->firstOrFail();

    $response->assertJsonPath('data.redirect_url', route('admin.sales.orders.view', $order->id));

    expect($order)
        ->status->toBe(Order::STATUS_PENDING)
        ->customer_id->toBe($customer->id)
        ->customer_email->toBe($customer->email)
        ->is_guest->toBeFalse()
        ->and((float) $order->grand_total)->toBePrice(200);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $product->id,
        'qty_ordered' => 2,
    ]);

    $this->assertDatabaseHas('order_payment', [
        'order_id' => $order->id,
        'method' => 'cashondelivery',
    ]);

    $this->assertDatabaseMissing('cart', ['id' => $cart->id]);
});

it('should refuse to place an order paid through an online payment method', function () {
    ['customer' => $customer, 'cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    prepareAdminCartForOrder($cart, $customer, $product, paymentMethod: 'paypal_smart_button');

    postJson(route('admin.sales.orders.store', $cart->id))
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.payment-not-supported'));

    $this->assertDatabaseMissing('orders', ['cart_id' => $cart->id]);
});

it('should refuse to place an order before a shipping address is given', function () {
    ['cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    postJson(route('admin.sales.orders.store', $cart->id))
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.check-shipping-address'));

    $this->assertDatabaseMissing('orders', ['cart_id' => $cart->id]);
});

it('should refuse to place an order before a shipping method is chosen', function () {
    ['customer' => $customer, 'cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    postJson(route('admin.sales.cart.addresses.store', $cart->id), [
        'billing' => adminCartAddress($customer),
    ]);

    postJson(route('admin.sales.orders.store', $cart->id))
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.specify-shipping-method'));

    $this->assertDatabaseMissing('orders', ['cart_id' => $cart->id]);
});

it('should refuse to place an order before a payment method is chosen', function () {
    ['customer' => $customer, 'cart' => $cart] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->loginAsAdmin();

    postJson(route('admin.sales.cart.items.store', $cart->id), [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    postJson(route('admin.sales.cart.addresses.store', $cart->id), [
        'billing' => adminCartAddress($customer),
    ]);

    postJson(route('admin.sales.cart.shipping_methods.store', $cart->id), [
        'shipping_method' => 'free_free',
    ]);

    postJson(route('admin.sales.orders.store', $cart->id))
        ->assertBadRequest()
        ->assertJsonPath('message', trans('admin::app.sales.orders.create.specify-payment-method'));

    $this->assertDatabaseMissing('orders', ['cart_id' => $cart->id]);
});

// ============================================================================
// Wishlist And Recent Orders
// ============================================================================

it('should list the wishlist items of the customer', function () {
    ['customer' => $customer] = createAdminCart();

    $product = $this->createSimpleProduct();

    $customer->wishlist_items()->create([
        'channel_id' => core()->getCurrentChannel()->id,
        'product_id' => $product->id,
    ]);

    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.wishlist.items', $customer->id))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.product.id', $product->id);
});

it('should list the recently ordered items of the customer', function () {
    ['customer' => $customer] = createAdminCart();

    $product = $this->createSimpleProduct();

    $this->createOrder(customer: $customer, items: [['product' => $product]]);

    $this->loginAsAdmin();

    getJson(route('admin.customers.customers.orders.recent_items', $customer->id))
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.product.id', $product->id);
});
