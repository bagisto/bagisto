<?php

use function Pest\Laravel\postJson;

/**
 * Build a guest checkout address payload.
 */
function guestAddress(): array
{
    return [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->safeEmail(),
        'address' => [fake()->streetAddress()],
        'city' => fake()->city(),
        'postcode' => fake()->postcode(),
        'phone' => fake()->numerify('##########'),
        'country' => 'US',
        'state' => 'CA',
    ];
}

// ============================================================================
// Address Validation
// ============================================================================

it('should fail validation when billing address is empty for guest', function () {
    $product = $this->createSimpleProduct();

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
});

it('should fail validation when billing address is empty for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [],
        'shipping' => [],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('billing.first_name');
});

it('should fail validation when use_for_shipping is false and shipping address is missing for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => false]),
        'shipping' => [],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipping.first_name');
});

it('should fail validation when use_for_shipping is false and shipping address is missing for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => false]),
        'shipping' => [],
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipping.first_name');
});

// ============================================================================
// Store Address
// ============================================================================

it('should store billing address as shipping when use_for_shipping is true for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
        'shipping' => $address,
    ])
        ->assertOk();
});

it('should store billing and shipping address for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge(guestAddress(), ['use_for_shipping' => false]),
        'shipping' => guestAddress(),
    ])
        ->assertOk();
});

it('should store billing address as shipping when use_for_shipping is true for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
        'shipping' => $address,
    ])
        ->assertOk();
});

it('should store billing and shipping address for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge(guestAddress(), ['use_for_shipping' => false]),
        'shipping' => guestAddress(),
    ])
        ->assertOk();
});

it('should store billing address for non-stockable items for guest', function () {
    $product = $this->createVirtualProduct();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge(guestAddress(), ['use_for_shipping' => true]),
        'shipping' => guestAddress(),
    ])
        ->assertOk();
});

// ============================================================================
// Store Shipping Method
// ============================================================================

it('should fail validation when shipping method is not provided for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.shipping_methods.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipping_method');
});

it('should fail validation when shipping method is not provided for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.shipping_methods.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('shipping_method');
});

it('should store the shipping method for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
        'shipping' => $address,
    ]);

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'free_free',
    ])
        ->assertOk();
});

it('should store the shipping method for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
        'shipping' => $address,
    ]);

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'free_free',
    ])
        ->assertOk();
});

// ============================================================================
// Store Payment Method
// ============================================================================

it('should fail validation when payment method is not provided for guest', function () {
    $product = $this->createSimpleProduct();

    $this->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.payment_methods.store'))
        ->assertUnprocessable()
        ->assertJsonValidationErrorFor('payment');
});

it('should store the payment method for guest', function () {
    $product = $this->createSimpleProduct();

    $this->prepareCartForCheckout($product->id);

    $this->getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.payment_method', 'cashondelivery');
});

it('should store the payment method for customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $this->prepareCartForCheckout($product->id);

    $this->getJson(route('shop.api.checkout.cart.index'))
        ->assertOk()
        ->assertJsonPath('data.payment_method', 'cashondelivery');
});

// ============================================================================
// Place Order — Simple Product
// ============================================================================

it('should place a simple product order for a guest user', function () {
    $product = $this->createSimpleProduct();

    $response = $this->placeOrder($product->id);

    $this->assertOrderPlaced($response);
});

it('should place a simple product order for a customer', function () {
    $product = $this->createSimpleProduct();

    $this->loginAsCustomer();

    $response = $this->placeOrder($product->id);

    $this->assertOrderPlaced($response);
});

// ============================================================================
// Place Order — Virtual Product
// ============================================================================

it('should place a virtual product order for a guest user', function () {
    $product = $this->createVirtualProduct();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
        'shipping' => $address,
    ]);

    postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'payment' => ['method' => 'cashondelivery'],
    ]);

    $response = postJson(route('shop.checkout.onepage.orders.store'));

    $this->assertOrderPlaced($response);
});

it('should place a virtual product order for a customer', function () {
    $product = $this->createVirtualProduct();

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
        'shipping' => $address,
    ]);

    postJson(route('shop.checkout.onepage.payment_methods.store'), [
        'payment' => ['method' => 'cashondelivery'],
    ]);

    $response = postJson(route('shop.checkout.onepage.orders.store'));

    $this->assertOrderPlaced($response);
});

// ============================================================================
// Non-Stockable Product Checkout Behavior
// ============================================================================

it('should not return shipping methods for virtual products', function () {
    $product = $this->createVirtualProduct();

    $this->addProductToCart($product->id);

    $address = guestAddress();

    $response = postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge($address, ['use_for_shipping' => true]),
        'shipping' => $address,
    ]);

    // Virtual products skip shipping — response goes directly to payment.
    $response->assertOk();
});
