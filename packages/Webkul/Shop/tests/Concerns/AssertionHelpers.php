<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Testing\TestResponse;

trait AssertionHelpers
{
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

        expect($itemsCount === null || $itemsCount === 0)->toBeTrue('Cart should be empty.');

        return $this;
    }

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
