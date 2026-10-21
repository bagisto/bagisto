<?php

use Webkul\Checkout\Facades\Cart;

// ============================================================================
// A Configurable Is Priced Only By Its Own Variants
// ============================================================================

it('should refuse a configurable option that is not one of the product\'s variants', function () {
    $configurable = $this->createConfigurableProduct([500, 900]);

    $foreign = $this->createSimpleProduct(['price' => ['float_value' => 1]]);

    expect($configurable->variants->pluck('id'))->not->toContain($foreign->id);

    expect(fn () => Cart::addProduct($configurable, [
        'product_id' => $configurable->id,
        'quantity' => 1,
        'selected_configurable_option' => $foreign->id,
    ]))->toThrow(Exception::class);

    expect(Cart::getCart())->toBeNull();
});

it('should still add a configurable by one of its own variants', function () {
    $configurable = $this->createConfigurableProduct([500, 900]);

    $variant = $configurable->variants->first();

    Cart::addProduct($configurable, [
        'product_id' => $configurable->id,
        'quantity' => 1,
        'selected_configurable_option' => (string) $variant->id,
    ]);

    expect((float) Cart::getCart()->items->firstWhere('parent_id', null)->base_price)
        ->toBe((float) $variant->price);
});

// ============================================================================
// A Bundle Is Priced Only By Its Own Options
// ============================================================================

it('should refuse a bundle option belonging to another bundle', function () {
    $bundle = $this->createBundleProduct([900]);

    $other = $this->createBundleProduct([1]);

    $foreignOption = $other->bundle_options->first();

    expect($bundle->bundle_options->pluck('id'))->not->toContain($foreignOption->id);

    expect(fn () => Cart::addProduct($bundle, [
        'product_id' => $bundle->id,
        'quantity' => 1,
        'bundle_options' => [$foreignOption->id => [$foreignOption->bundle_option_products->first()->id]],
    ]))->toThrow(Exception::class);

    expect(Cart::getCart())->toBeNull();
});

it('should still add a bundle by one of its own options', function () {
    $bundle = $this->createBundleProduct([900]);

    $option = $bundle->bundle_options->first();

    Cart::addProduct($bundle, [
        'product_id' => $bundle->id,
        'quantity' => 1,
        'bundle_options' => [(string) $option->id => [$option->bundle_option_products->first()->id]],
    ]);

    expect((float) Cart::getCart()->grand_total)->toBe(900.0);
});
