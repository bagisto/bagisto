<?php

use Illuminate\Support\Facades\Cache;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Core\Models\CoreConfig;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/**
 * Turn guest checkout on or off, past the repository's cache.
 */
function setGuestCheckout(bool $allowed): void
{
    CoreConfig::updateOrCreate(
        [
            'code' => 'sales.checkout.shopping_cart.allow_guest_checkout',
            'channel_code' => null,
            'locale_code' => null,
        ],
        ['value' => $allowed ? '1' : '0'],
    );

    Cache::flush();
}

/**
 * A storefront billing address that is also used for shipping.
 */
function guestCheckoutAddress(): array
{
    return [
        'first_name' => fake()->firstName(),
        'last_name' => fake()->lastName(),
        'email' => fake()->safeEmail(),
        'address' => [fake()->streetAddress()],
        'city' => fake()->city(),
        'country' => 'US',
        'state' => 'CA',
        'postcode' => '90001',
        'phone' => fake()->e164PhoneNumber(),
        'use_for_shipping' => 1,
    ];
}

/**
 * Put one unit of a new simple product in the guest's cart, returning the cart id.
 */
function guestCartWithProduct(): int
{
    return postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => test()->createSimpleProduct()->id,
        'quantity' => 1,
    ])->assertOk()->json('data.id');
}

afterEach(fn () => setGuestCheckout(true));

// ============================================================================
// The Checkout Api Holds A Guest To The Same Rules As The Page
// ============================================================================

it('should send a guest to sign in from the address step while guest checkout is off', function () {
    setGuestCheckout(false);

    $cartId = guestCartWithProduct();

    postJson(route('shop.checkout.onepage.addresses.store'), ['billing' => guestCheckoutAddress()])
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.customer.session.index'));

    $this->assertDatabaseMissing('addresses', [
        'cart_id' => $cartId,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
    ]);
});

it('should not place a guest order when guest checkout is turned off before the order is placed', function () {
    $cartId = guestCartWithProduct();

    postJson(route('shop.checkout.onepage.addresses.store'), ['billing' => guestCheckoutAddress()])->assertOk();

    postJson(route('shop.checkout.onepage.shipping_methods.store'), ['shipping_method' => 'free_free'])->assertOk();

    postJson(route('shop.checkout.onepage.payment_methods.store'), ['payment' => ['method' => 'cashondelivery']])->assertOk();

    setGuestCheckout(false);

    postJson(route('shop.checkout.onepage.orders.store'))
        ->assertOk()
        ->assertJsonPath('data.redirect', true)
        ->assertJsonPath('data.redirect_url', route('shop.customer.session.index'));

    $this->assertDatabaseMissing('orders', ['cart_id' => $cartId]);
});

it('should send a guest back to checkout from the paypal standard redirect while guest checkout is off', function () {
    guestCartWithProduct();

    setGuestCheckout(false);

    get(route('paypal.standard.redirect'))
        ->assertRedirect(route('shop.checkout.onepage.index'));
});
