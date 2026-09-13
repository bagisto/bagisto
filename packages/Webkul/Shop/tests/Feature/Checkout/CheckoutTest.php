<?php

use Illuminate\Support\Facades\Hash;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Customer\Models\Customer;
use Webkul\Sales\Models\Order;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\postJson;

/**
 * Switch guest checkout on or off for the storefront.
 */
function allowGuestCheckout(bool $allowed): void
{
    test()->setConfig('sales.checkout.shopping_cart.allow_guest_checkout', $allowed ? '1' : '0');
}

// ============================================================================
// Address Validation
// ============================================================================

it('should fail validation when the billing address is empty', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [],
        'shipping' => [],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('billing.first_name')
        ->assertJsonValidationErrorFor('billing.last_name')
        ->assertJsonValidationErrorFor('billing.email')
        ->assertJsonValidationErrorFor('billing.address')
        ->assertJsonValidationErrorFor('billing.city')
        ->assertJsonValidationErrorFor('billing.phone');
})->with('shoppers');

it('should still demand a shipping address when the cart holds a stockable item', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $this->storefrontAddress(['use_for_shipping' => false]),
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipping.first_name')
        ->assertJsonValidationErrorFor('shipping.address');

    $this->assertDatabaseMissing('addresses', ['cart_id' => $cartId]);
})->with('shoppers');

// ============================================================================
// Store Address
// ============================================================================

it('should copy the billing address to the shipping address when use_for_shipping is on', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    $address = $this->storefrontAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonStructure(['data' => ['shippingMethods']]);

    foreach ([CartAddress::ADDRESS_TYPE_BILLING, CartAddress::ADDRESS_TYPE_SHIPPING] as $type) {
        $this->assertDatabaseHas('addresses', [
            'cart_id' => $cartId,
            'address_type' => $type,
            'first_name' => $address['first_name'],
            'city' => $address['city'],
        ]);
    }
})->with('shoppers');

it('should store separate billing and shipping addresses', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    $billing = $this->storefrontAddress(['use_for_shipping' => false]);

    $shipping = $this->storefrontAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $billing,
        'shipping' => $shipping,
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false);

    $this->assertDatabaseHas('addresses', [
        'cart_id' => $cartId,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        'first_name' => $billing['first_name'],
    ]);

    $this->assertDatabaseHas('addresses', [
        'cart_id' => $cartId,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
        'first_name' => $shipping['first_name'],
    ]);
})->with('shoppers');

it('should take the guest details for the cart from the billing address', function () {
    $product = $this->createSimpleProduct();

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    $address = $this->storefrontAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
    ])->assertOk();

    $this->assertDatabaseHas('cart', [
        'id' => $cartId,
        'customer_email' => $address['email'],
        'customer_first_name' => $address['first_name'],
        'customer_last_name' => $address['last_name'],
    ]);
});

it('should store only a billing address for a non-stockable cart and offer the payment methods', function (bool $signedIn) {
    $product = $this->createVirtualProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => $this->storefrontAddress(['use_for_shipping' => false]),
    ])
        ->assertOk()
        ->assertJsonPath('redirect', false)
        ->assertJsonStructure(['data' => ['payment_methods']]);

    $this->assertDatabaseHas('addresses', [
        'cart_id' => $cartId,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);

    $this->assertDatabaseMissing('addresses', [
        'cart_id' => $cartId,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
    ]);
})->with('shoppers');

it('should send a guest to sign in from the address step when the cart holds a product that needs an account', function () {
    $product = $this->createSimpleProduct(['guest_checkout' => ['boolean_value' => false]]);

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
    ])
        ->assertOk()
        ->assertJsonPath('redirect', true)
        ->assertJsonPath('data', route('shop.customer.session.index'))
        ->assertSessionHas('shop.url.intended', route('shop.checkout.onepage.index'));
});

// ============================================================================
// Shipping Method
// ============================================================================

it('should fail validation when the shipping method is not provided', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.shipping_methods.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipping_method');
})->with('shoppers');

it('should store the shipping method and offer the payment methods', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
    ]);

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'free_free',
    ])
        ->assertOk()
        ->assertJsonStructure(['payment_methods']);

    $this->assertDatabaseHas('cart', [
        'id' => $cartId,
        'shipping_method' => 'free_free',
    ]);

    $this->assertDatabaseHas('cart_shipping_rates', [
        'cart_id' => $cartId,
        'method' => 'free_free',
    ]);
})->with('shoppers');

it('should refuse a shipping method the store does not offer', function () {
    $product = $this->createSimpleProduct();

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
    ]);

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'courier_overnight',
    ])
        ->assertForbidden()
        ->assertJsonPath('redirect_url', route('shop.checkout.cart.index'));

    $this->assertDatabaseHas('cart', [
        'id' => $cartId,
        'shipping_method' => null,
    ]);
});

// ============================================================================
// Payment Method
// ============================================================================

it('should fail validation when the payment method is not provided', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.payment_methods.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('payment');
});

it('should store the payment method', function (bool $signedIn) {
    $product = $this->createSimpleProduct();

    if ($signedIn) {
        $this->loginAsCustomer();
    }

    $cartId = $this->prepareCartForCheckout($product->id);

    $this->getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.payment_method', 'cashondelivery');

    $this->assertDatabaseHas('cart_payment', [
        'cart_id' => $cartId,
        'method' => 'cashondelivery',
    ]);
})->with('shoppers');

it('should refuse a payment method that is not available for the cart', function () {
    $product = $this->createSimpleProduct();

    $cartId = $this->addProductToCart($product->id)->json('data.id');

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($this->storefrontAddress(), ['use_for_shipping' => true]),
    ]);

    postJson(route('shop.checkout.onepage.shipping_methods.store'), ['shipping_method' => 'free_free']);

    postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'payment' => ['method' => 'unknown_gateway'],
    ])
        ->assertForbidden()
        ->assertJsonPath('redirect_url', route('shop.checkout.cart.index'));

    $this->assertDatabaseMissing('cart_payment', ['cart_id' => $cartId]);
});

// ============================================================================
// Place Order
// ============================================================================

it('should place an order for a guest with the billing address as the customer details', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $address = $this->storefrontAddress();

    $order = $this->assertOrderPlaced($this->placeOrder($product->id, $address));

    expect($order)
        ->is_guest->toBeTrue()
        ->customer_id->toBeNull()
        ->customer_email->toBe($address['email'])
        ->customer_first_name->toBe($address['first_name'])
        ->and((float) $order->grand_total)->toBePrice(100);

    $this->assertDatabaseHas('order_items', [
        'order_id' => $order->id,
        'product_id' => $product->id,
        'qty_ordered' => 1,
    ]);

    $this->assertDatabaseHas('product_ordered_inventories', [
        'product_id' => $product->id,
        'channel_id' => $order->channel_id,
        'qty' => 1,
    ]);
});

it('should place an order for a signed-in customer and attach it to the account', function () {
    $product = $this->createSimpleProduct();

    $customer = $this->loginAsCustomer();

    $order = $this->assertOrderPlaced($this->placeOrder($product->id));

    expect($order)
        ->is_guest->toBeFalse()
        ->customer_id->toBe($customer->id)
        ->customer_email->toBe($customer->email);

    expect($customer->orders()->pluck('id')->all())->toBe([$order->id]);
});

it('should place an order for a product of every type', function (string $type) {
    $product = $this->createProductOfType($type);

    $this->loginAsCustomer();

    $cartId = $this->prepareCartForCheckout($product);

    $grandTotal = (float) $this->getJson(route('shop.api.checkout.cart.index'))->json('data.grand_total');

    $order = $this->assertOrderPlaced(postJson(route('shop.checkout.onepage.orders.store')));

    expect($order->cart_id)->toBe($cartId)
        ->and((float) $order->grand_total)->toBePrice($grandTotal)
        ->and($order->items()->count())->toBeGreaterThan(0);
})->with('product types');

it('should not place a second order from a cart that has already been ordered', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $order = $this->assertOrderPlaced($this->placeOrder($product->id));

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.cart.index'));

    expect(Order::query()->where('cart_id', $order->cart_id)->count())->toBe(1);
});

it('should refuse to place the order when the customer account is suspended', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer(Customer::factory()->create(['is_suspended' => 1]));

    $this->prepareCartForCheckout($product->id);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertServerError()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.suspended-account-message'));

    $this->assertDatabaseMissing('orders', ['customer_email' => auth()->guard('customer')->user()->email]);
});

it('should refuse to place the order below the minimum order amount', function () {
    $this->setConfig([
        'sales.order_settings.minimum_order.enable' => '1',
        'sales.order_settings.minimum_order.minimum_order_amount' => '500',
    ]);

    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $address = $this->storefrontAddress();

    $this->placeOrder($product->id, $address)
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.cart.index'));

    $this->assertDatabaseMissing('orders', ['customer_email' => $address['email']]);
});

it('should refuse to place the order before a payment method is chosen', function () {
    $product = $this->createSimpleProduct();

    $address = $this->storefrontAddress();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
    ]);

    postJson(route('shop.checkout.onepage.shipping_methods.store'), ['shipping_method' => 'free_free']);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertServerError()
        ->assertJsonPath('message', trans('shop::app.checkout.cart.specify-payment-method'));

    $this->assertDatabaseMissing('orders', ['customer_email' => $address['email']]);
});

it('should refuse to place the order when the stock ran out after the product was added', function () {
    $product = $this->setProductStock($this->createSimpleProduct(), 5);

    $address = $this->storefrontAddress();

    $this->prepareCartForCheckout($product->id, $address, quantity: 2);

    $this->setProductStock($product, 1);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.checkout.cart.index'));

    $this->assertDatabaseMissing('orders', ['customer_email' => $address['email']]);
});

// ============================================================================
// Guest Checkout
// ============================================================================

it('should keep a guest on the checkout page when guest checkout is allowed', function () {
    allowGuestCheckout(true);

    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    get(route('shop.checkout.onepage.index'))
        ->assertOk();
});

it('should send a guest to sign in when guest checkout is off and bring them back to checkout after signing in', function () {
    allowGuestCheckout(false);

    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    get(route('shop.checkout.onepage.index'))
        ->assertRedirect(route('shop.customer.session.index'))
        ->assertSessionHas('shop.url.intended', route('shop.checkout.onepage.index'));

    $customer = Customer::factory()->create(['password' => Hash::make('secret-password')]);

    post(route('shop.customer.session.create'), [
        'email' => $customer->email,
        'password' => 'secret-password',
    ])
        ->assertRedirect(route('shop.checkout.onepage.index'));

    $this->assertAuthenticatedAs($customer, 'customer');
});
