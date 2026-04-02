<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Testing\TestResponse;

trait CheckoutHelpers
{
    /**
     * Build a cart ready for order placement: product added, addresses
     * saved, shipping and payment selected. Returns the cart ID.
     */
    public function prepareCartForCheckout(
        int $productId,
        array $addressOverrides = [],
        string $shippingMethod = 'free_free',
        string $paymentMethod = 'cashondelivery',
    ): int {
        $cartResponse = $this->addProductToCart($productId);
        $cartId = $cartResponse->json('data.id');

        $address = array_merge([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'address' => [fake()->streetAddress()],
            'city' => fake()->city(),
            'postcode' => fake()->postcode(),
            'phone' => fake()->numerify('##########'),
            'country' => 'US',
            'state' => 'CA',
        ], $addressOverrides);

        $this->postJson(route('shop.checkout.onepage.addresses.store'), [
            'billing' => array_merge($address, ['use_for_shipping' => true]),
            'shipping' => $address,
        ]);

        $this->postJson(route('shop.checkout.onepage.shipping_methods.store'), [
            'shipping_method' => $shippingMethod,
        ]);

        $this->postJson(route('shop.checkout.onepage.payment_methods.store'), [
            'payment' => ['method' => $paymentMethod],
        ]);

        return $cartId;
    }

    /**
     * Place an order for a product. Returns the order placement response.
     */
    public function placeOrder(int $productId, array $addressOverrides = []): TestResponse
    {
        $this->prepareCartForCheckout($productId, $addressOverrides);

        return $this->postJson(route('shop.checkout.onepage.orders.store'));
    }
}
