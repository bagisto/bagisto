<?php

use Webkul\Checkout\Exceptions\BillingAddressNotFoundException;
use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Customer\Models\Customer;
use Webkul\Customer\Models\CustomerAddress;

/**
 * A guest cart holding one unit of the given product type, with no addresses yet.
 */
function cartAwaitingAddresses(string $productType = 'simple'): CartModel
{
    $product = $productType === 'virtual'
        ? test()->createVirtualProduct(['price' => ['float_value' => 100]])
        : test()->createSimpleProduct(['price' => ['float_value' => 100]]);

    $cart = CartModel::factory()->create([
        'customer_id' => null,
        'is_guest' => true,
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'quantity' => 1,
        'name' => $product->name,
        'price' => 100,
        'base_price' => 100,
        'total' => 100,
        'base_total' => 100,
        'type' => $product->type,
        'additional' => ['product_id' => $product->id, 'quantity' => 1],
    ]);

    Cart::setCart($cart);

    return $cart->fresh();
}

/**
 * Request-shaped address params, whose `address` arrives as lines rather than a joined string.
 */
function addressParams(array $overrides = []): array
{
    return array_merge([
        'first_name' => 'Asha',
        'last_name' => 'Rao',
        'email' => 'asha@example.test',
        'address' => ['12 Linking Road', 'Bandra'],
        'country' => 'IN',
        'state' => 'MH',
        'city' => 'Mumbai',
        'postcode' => '400050',
        'phone' => '+919900000000',
        'use_for_shipping' => false,
    ], $overrides);
}

// ============================================================================
// Saving Addresses Writes Both Sides And The Cart's Own Details
// ============================================================================

it('should copy the billing address to shipping when asked to use it for shipping', function () {
    cartAwaitingAddresses();

    Cart::saveAddresses(['billing' => addressParams(['use_for_shipping' => true])]);

    $cart = Cart::getCart();

    expect($cart->billing_address)->not->toBeNull()
        ->and($cart->shipping_address)->not->toBeNull()
        ->and($cart->shipping_address->first_name)->toBe('Asha')
        ->and($cart->shipping_address->country)->toBe('IN')
        ->and($cart->shipping_address->address)->toBe($cart->billing_address->address);
});

it('should keep a posted shipping address separate from the billing one', function () {
    cartAwaitingAddresses();

    Cart::saveAddresses([
        'billing' => addressParams(),
        'shipping' => addressParams([
            'first_name' => 'Devi',
            'address' => ['99 Market Street'],
            'country' => 'US',
            'state' => 'CA',
            'city' => 'San Francisco',
            'postcode' => '94103',
        ]),
    ]);

    $cart = Cart::getCart();

    expect($cart->billing_address->country)->toBe('IN')
        ->and($cart->shipping_address->country)->toBe('US')
        ->and($cart->shipping_address->first_name)->toBe('Devi');
});

it('should take the cart customer details off the billing address it just saved', function () {
    cartAwaitingAddresses();

    Cart::saveAddresses(['billing' => addressParams(['use_for_shipping' => true])]);

    $cart = Cart::getCart();

    expect($cart->customer_email)->toBe('asha@example.test')
        ->and($cart->customer_first_name)->toBe('Asha')
        ->and($cart->customer_last_name)->toBe('Rao');
});

it('should join the posted address lines into one stored string', function () {
    cartAwaitingAddresses();

    Cart::saveAddresses(['billing' => addressParams()]);

    expect(Cart::getCart()->billing_address->address)->toBe("12 Linking Road\nBandra");
});

// ============================================================================
// The Saved Customer Address A Cart Address Was Taken From
// ============================================================================

it('should link a cart address to the saved customer address it came from', function () {
    $customer = Customer::factory()->create();

    $savedAddress = CustomerAddress::factory()->create([
        'customer_id' => $customer->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cart = cartAwaitingAddresses();

    $cart->customer_id = $customer->id;

    $cart->is_guest = false;

    $cart->save();

    Cart::setCart($cart->fresh());

    Cart::saveAddresses(['billing' => addressParams([
        'id' => $savedAddress->id,
        'address_type' => 'customer',
        'use_for_shipping' => true,
    ])]);

    expect(Cart::getCart()->billing_address->parent_address_id)->toBe($savedAddress->id);
});

it('should not link a cart address to a saved address belonging to someone else', function () {
    $customer = Customer::factory()->create();

    $someoneElse = CustomerAddress::factory()->create([
        'customer_id' => Customer::factory()->create()->id,
        'address_type' => CustomerAddress::ADDRESS_TYPE,
    ]);

    $cart = cartAwaitingAddresses();

    $cart->customer_id = $customer->id;

    $cart->is_guest = false;

    $cart->save();

    Cart::setCart($cart->fresh());

    Cart::saveAddresses(['billing' => addressParams([
        'id' => $someoneElse->id,
        'address_type' => 'customer',
        'use_for_shipping' => true,
    ])]);

    expect(Cart::getCart()->billing_address->parent_address_id)->toBeNull();
});

// ============================================================================
// What A Shipping Address Is Not Written For
// ============================================================================

it('should write no shipping address for a cart holding nothing stockable', function () {
    cartAwaitingAddresses('virtual');

    Cart::saveAddresses(['billing' => addressParams(['use_for_shipping' => true])]);

    expect(Cart::getCart()->shipping_address)->toBeNull();
});

it('should refuse to write a shipping address before a billing address exists', function () {
    cartAwaitingAddresses();

    expect(fn () => Cart::updateOrCreateShippingAddress(addressParams()))
        ->toThrow(BillingAddressNotFoundException::class);
});

it('should never create a shipping address that is the customer default', function () {
    cartAwaitingAddresses();

    Cart::saveAddresses([
        'billing' => addressParams(['default_address' => 1]),
        'shipping' => addressParams(['default_address' => 1, 'country' => 'US', 'state' => 'CA']),
    ]);

    expect((int) Cart::getCart()->shipping_address->default_address)->toBe(0);
});

// ============================================================================
// Only The Whitelisted Fields Reach The Database
// ============================================================================

it('should ignore a posted field that is not one an address is built from', function () {
    cartAwaitingAddresses();

    Cart::saveAddresses(['billing' => addressParams(['cart_id' => 99999, 'address_type' => 'nonsense'])]);

    $cart = Cart::getCart();

    expect($cart->billing_address->cart_id)->toBe($cart->id)
        ->and($cart->billing_address->address_type)->toBe(CartAddress::ADDRESS_TYPE_BILLING);
});

it('should keep a vat id on billing and leave it off shipping, which is not built from one', function () {
    cartAwaitingAddresses();

    Cart::saveAddresses([
        'billing' => addressParams(['vat_id' => 'GB123456789']),
        'shipping' => addressParams(['vat_id' => 'GB999999999', 'country' => 'US', 'state' => 'CA']),
    ]);

    $cart = Cart::getCart();

    expect($cart->billing_address->vat_id)->toBe('GB123456789')
        ->and($cart->shipping_address->vat_id)->toBeNull();
});
