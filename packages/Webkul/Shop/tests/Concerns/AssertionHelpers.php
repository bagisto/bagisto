<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Testing\TestResponse;
use Webkul\Sales\Models\Order;

trait AssertionHelpers
{
    /**
     * Assert the cart contains a specific product with the given quantity.
     */
    public function assertCartHasProduct(int $productId, int $expectedQty = 1): static
    {
        $cartId = $this->getJson(route('shop.api.checkout.cart.index'))->json('data.id');

        expect($cartId)->not->toBeNull("Product {$productId} should be in the cart, but there is no cart.");

        $this->assertDatabaseHas('cart_items', [
            'cart_id' => $cartId,
            'product_id' => $productId,
            'quantity' => $expectedQty,
        ]);

        return $this;
    }

    /**
     * Assert the cart is empty (no items).
     */
    public function assertCartIsEmpty(): static
    {
        $response = $this->getJson(route('shop.api.checkout.cart.index'));

        $itemsCount = $response->json('data.items_count');

        expect($itemsCount === null || $itemsCount === 0)->toBeTrue('Cart should be empty.');

        return $this;
    }

    /**
     * Assert the price of the cart item at the given position, counting the items in the order they were added.
     */
    public function assertCartItemPrice(TestResponse $response, float $expectedPrice, int $itemIndex = 0): static
    {
        $items = collect($response->json('data.items'))->sortBy('id')->values();

        $this->assertPrice($expectedPrice, data_get($items, "{$itemIndex}.price"));

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
     * Assert an order was placed from the response: the customer is sent to the success page,
     * the order row exists with its payment, and the cart it came from is no longer active.
     */
    public function assertOrderPlaced(TestResponse $response): Order
    {
        $response->assertOk()
            ->assertJsonPath('data.redirect', true)
            ->assertJsonPath('data.redirect_url', route('shop.checkout.onepage.success'))
            ->assertSessionHas('order_id');

        $order = Order::query()->findOrFail(session('order_id'));

        expect($order->status)->toBe(Order::STATUS_PENDING);

        $this->assertDatabaseHas('order_payment', ['order_id' => $order->id]);

        $this->assertDatabaseHas('order_items', ['order_id' => $order->id]);

        $this->assertDatabaseHas('cart', [
            'id' => $order->cart_id,
            'is_active' => false,
        ]);

        return $order;
    }
}
