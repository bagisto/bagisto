<?php

namespace Webkul\Shop\Tests\Concerns;

use Illuminate\Testing\TestResponse;
use Webkul\Product\Models\Product;

trait CartHelpers
{
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
     * Smart add-to-cart that resolves the correct payload based on product type.
     */
    public function addProductByType(Product $product, int $qty = 1): TestResponse
    {
        return match ($product->type) {
            'simple', 'virtual' => $this->addProductToCart($product->id, $qty),
            'configurable' => $this->addConfigurableProductToCart($product, $qty),
            'grouped' => $this->addGroupedProductToCart($product, $qty),
            'bundle' => $this->addBundleProductToCart($product, $qty),
            'downloadable' => $this->addDownloadableProductToCart($product, $qty),
            default => throw new \InvalidArgumentException("Unsupported product type: {$product->type}"),
        };
    }

    /**
     * Add a configurable product's first variant to the cart.
     */
    public function addConfigurableProductToCart(Product $product, int $qty = 1): TestResponse
    {
        $product->loadMissing('variants');

        $variant = $product->variants->firstOrFail();

        return $this->addProductToCart($product->id, $qty, [
            'selected_configurable_option' => $variant->id,
        ]);
    }

    /**
     * Add all associated products of a grouped product to the cart.
     */
    public function addGroupedProductToCart(Product $product, int $qty = 1): TestResponse
    {
        $product->loadMissing('grouped_products');

        $qtys = $product->grouped_products
            ->pluck('associated_product_id')
            ->mapWithKeys(fn ($id) => [$id => $qty])
            ->toArray();

        return $this->addProductToCart($product->id, 1, ['qty' => $qtys]);
    }

    /**
     * Add a bundle product's first option to the cart.
     */
    public function addBundleProductToCart(Product $product, int $qty = 1): TestResponse
    {
        $product->loadMissing('bundle_options.bundle_option_products');

        $option = $product->bundle_options->firstOrFail();
        $optionProduct = $option->bundle_option_products->firstOrFail();

        return $this->addProductToCart($product->id, $qty, [
            'bundle_options' => [$option->id => [$optionProduct->id]],
            'bundle_option_qty' => [$option->id => $qty],
        ]);
    }

    /**
     * Add a downloadable product with all its links to the cart.
     */
    public function addDownloadableProductToCart(Product $product, int $qty = 1): TestResponse
    {
        $product->loadMissing('downloadable_links');

        return $this->addProductToCart($product->id, $qty, [
            'links' => $product->downloadable_links->pluck('id')->toArray(),
        ]);
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
}
