<?php

use Illuminate\Support\Facades\Event;

/**
 * Add a bundle product's first option to cart.
 */
function addBundleToCartForSpecialPrice($test, $product)
{
    $product->load('bundle_options.bundle_option_products');

    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    return $test->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ]);
}

// ============================================================================
// Active Special Price
// ============================================================================

it('should apply special price to bundle option product when within date range', function () {
    $product = $this->createBundleProduct([1000]);

    $option = $product->bundle_options->first();
    $optionSimple = $option->bundle_option_products->first()->product;

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 700]);

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price_from']->id)
        ->update(['date_value' => now()->subDay()->format('Y-m-d')]);

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price_to']->id)
        ->update(['date_value' => now()->addMonth()->format('Y-m-d')]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    $response = addBundleToCartForSpecialPrice($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

it('should use regular price when bundle option product special price has expired', function () {
    $product = $this->createBundleProduct([1000]);

    $option = $product->bundle_options->first();
    $optionSimple = $option->bundle_option_products->first()->product;

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 700]);

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price_from']->id)
        ->update(['date_value' => now()->subMonth()->format('Y-m-d')]);

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price_to']->id)
        ->update(['date_value' => now()->subDay()->format('Y-m-d')]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    $response = addBundleToCartForSpecialPrice($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should use regular price when bundle option product special price has not started', function () {
    $product = $this->createBundleProduct([1000]);

    $option = $product->bundle_options->first();
    $optionSimple = $option->bundle_option_products->first()->product;

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 700]);

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price_from']->id)
        ->update(['date_value' => now()->addDay()->format('Y-m-d')]);

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price_to']->id)
        ->update(['date_value' => now()->addMonth()->format('Y-m-d')]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    $response = addBundleToCartForSpecialPrice($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should apply special price to bundle option product when no date range is set', function () {
    $product = $this->createBundleProduct([1000]);

    $option = $product->bundle_options->first();
    $optionSimple = $option->bundle_option_products->first()->product;

    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 750]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    $response = addBundleToCartForSpecialPrice($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 750);
});
