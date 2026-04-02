<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Testing\TestResponse;
use Webkul\Admin\Tests\Concerns\ProductTestBench;
use Webkul\Core\Models\Channel;
use Webkul\Customer\Contracts\Customer as CustomerContract;
use Webkul\Customer\Models\Customer;

trait ShopTestBench
{
    use ProductTestBench;

    // ========================================================================
    // Authentication
    // ========================================================================

    /**
     * Login as a shop customer.
     */
    public function loginAsCustomer(?CustomerContract $customer = null): CustomerContract
    {
        $customer = $customer ?? Customer::factory()->create();

        $this->actingAs($customer);

        return $customer;
    }

    /**
     * Ensure the request is treated as a guest (no auth).
     */
    public function asGuest(): static
    {
        auth()->guard('customer')->logout();

        return $this;
    }

    // ========================================================================
    // Channel
    // ========================================================================

    /**
     * Simulate requests to a specific channel via hostname.
     *
     * Sets HTTP_HOST so core()->getCurrentChannel() resolves
     * the channel from hostname, exactly like a real browser request.
     * The Shop middleware pipeline (Theme, Locale, Currency) then
     * runs on the resolved channel.
     */
    public function forChannel(Channel $channel): static
    {
        $hostname = $channel->hostname
            ? parse_url($channel->hostname, PHP_URL_HOST) ?? $channel->hostname
            : 'localhost';

        $this->withServerVariables(['HTTP_HOST' => $hostname]);

        return $this;
    }

    // ========================================================================
    // Cart
    // ========================================================================

    /**
     * Add a product to the cart via the real API endpoint.
     */
    public function addProductToCart(int $productId, int $quantity = 1, array $extra = []): TestResponse
    {
        return $this->postJson(route('shop.api.checkout.cart.store'), array_merge([
            'product_id' => $productId,
            'quantity' => $quantity,
            'is_buy_now' => 0,
        ], $extra));
    }

    /**
     * Update a cart item quantity via the real API endpoint.
     */
    public function updateCartItem(int $cartItemId, int $quantity): TestResponse
    {
        return $this->putJson(route('shop.api.checkout.cart.update'), [
            'qty' => [$cartItemId => $quantity],
        ]);
    }

    /**
     * Remove a cart item via the real API endpoint.
     */
    public function removeCartItem(int $cartItemId): TestResponse
    {
        return $this->deleteJson(route('shop.api.checkout.cart.destroy'), [
            'cart_item_id' => $cartItemId,
        ]);
    }

    /**
     * Apply a coupon code to the cart.
     */
    public function applyCoupon(string $code): TestResponse
    {
        return $this->postJson(route('shop.api.checkout.cart.coupon.apply'), [
            'code' => $code,
        ]);
    }

    // ========================================================================
    // Checkout
    // ========================================================================

    /**
     * Build a cart ready for order placement: product added, addresses
     * saved, shipping and payment selected. Returns the cart ID.
     *
     * Shipping and payment default to the universally available methods
     * but can be overridden for tests that need specific methods.
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
     *
     * Combines prepareCartForCheckout + storeOrder into a single call
     * for tests that only care about the final result.
     */
    public function placeOrder(int $productId, array $addressOverrides = []): TestResponse
    {
        $this->prepareCartForCheckout($productId, $addressOverrides);

        return $this->postJson(route('shop.checkout.onepage.orders.store'));
    }

    // ========================================================================
    // Assertions
    // ========================================================================

    /**
     * Assert the cart contains a specific product with the given quantity.
     */
    public function assertCartHasProduct(int $productId, int $expectedQty = 1): static
    {
        $response = $this->getJson(route('shop.api.checkout.cart.index'));

        $items = collect($response->json('data.items'));

        $item = $items->firstWhere('product_id', $productId);

        expect($item)->not->toBeNull("Product {$productId} should be in the cart.");
        expect((int) $item['quantity'])->toBe($expectedQty, "Product {$productId} quantity should be {$expectedQty}.");

        return $this;
    }

    /**
     * Assert the cart is empty (no items).
     */
    public function assertCartIsEmpty(): static
    {
        $response = $this->getJson(route('shop.api.checkout.cart.index'));

        $itemsCount = $response->json('data.items_count');

        // After removing the last item, the cart may be null (deactivated).
        expect($itemsCount === null || $itemsCount === 0)->toBeTrue('Cart should be empty.');

        return $this;
    }

    // ========================================================================
    // Pricing Helpers
    // ========================================================================

    /**
     * Assert the cart item price matches the expected value.
     */
    public function assertCartItemPrice(TestResponse $response, float $expectedPrice, int $itemIndex = 0): static
    {
        $this->assertPrice($expectedPrice, $response->json("data.items.{$itemIndex}.price"));

        return $this;
    }

    /**
     * Assert the cart discount amount matches the expected value.
     */
    public function assertCartDiscount(TestResponse $response, float $expectedDiscount): static
    {
        $this->assertPrice($expectedDiscount, $response->json('data.discount_amount'));

        return $this;
    }

    /**
     * Assert the cart grand total matches the expected value.
     */
    public function assertCartGrandTotal(float $expectedTotal): static
    {
        $response = $this->getJson(route('shop.api.checkout.cart.index'));

        $this->assertPrice($expectedTotal, $response->json('data.grand_total'));

        return $this;
    }

    /**
     * Assert an order was placed successfully.
     */
    public function assertOrderPlaced(TestResponse $response): static
    {
        $response->assertOk();

        $data = $response->json('data');

        expect($data['redirect'])->toBeTrue('Order should redirect to success page.');
        expect($data['redirect_url'])->toContain('checkout/onepage/success');

        return $this;
    }
}
