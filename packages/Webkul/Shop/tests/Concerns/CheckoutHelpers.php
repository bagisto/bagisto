<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Testing\TestResponse;
use Webkul\Product\Models\Product;

trait CheckoutHelpers
{
    /**
     * A complete storefront address payload, with a state that belongs to its country.
     */
    public function storefrontAddress(array $overrides = []): array
    {
        return array_merge([
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'address' => [fake()->streetAddress()],
            'city' => fake()->city(),
            'postcode' => fake()->numerify('#####'),
            'phone' => fake()->numerify('##########'),
            'country' => 'US',
            'state' => 'CA',
        ], $overrides);
    }

    /**
     * Build a cart ready for order placement: product added, addresses saved, shipping and
     * payment selected. Cash on delivery is chosen for a cart that ships, money transfer
     * otherwise, unless a payment method is given. Returns the cart ID.
     */
    public function prepareCartForCheckout(
        int|Product $product,
        array $addressOverrides = [],
        string $shippingMethod = 'free_free',
        ?string $paymentMethod = null,
        int $quantity = 1,
    ): int {
        $cartResponse = $product instanceof Product
            ? $this->addProductByType($product, $quantity)
            : $this->addProductToCart($product, $quantity);

        $cartResponse->assertOk();

        $cartId = $cartResponse->json('data.id');

        $stockable = (bool) $cartResponse->json('data.have_stockable_items');

        $address = $this->storefrontAddress($addressOverrides);

        $this->postJson(route('shop.checkout.onepage.addresses.store'), [
            'billing' => array_merge($address, ['use_for_shipping' => true]),
            'shipping' => $address,
        ]);

        if ($stockable) {
            $this->postJson(route('shop.checkout.onepage.shipping_methods.store'), [
                'shipping_method' => $shippingMethod,
            ]);
        }

        $this->postJson(route('shop.checkout.onepage.payment_methods.store'), [
            'payment' => ['method' => $paymentMethod ?? ($stockable ? 'cashondelivery' : 'moneytransfer')],
        ]);

        return $cartId;
    }

    /**
     * Place an order for a product. Returns the order placement response.
     */
    public function placeOrder(int|Product $product, array $addressOverrides = []): TestResponse
    {
        $this->prepareCartForCheckout($product, $addressOverrides);

        return $this->postJson(route('shop.checkout.onepage.orders.store'));
    }
}
