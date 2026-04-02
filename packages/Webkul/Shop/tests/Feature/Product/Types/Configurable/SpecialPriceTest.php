<?php

use Illuminate\Support\Facades\Event;

// ============================================================================
// Active Special Price
// ============================================================================

it('should apply special price to configurable variant when within date range', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    // Set special price on the variant.
    $variant->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $variant);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should use regular price when configurable variant special price has expired', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 1000);
});
