<?php

// ============================================================================
// Active Special Price
// ============================================================================

it('should apply special price to bundle option product when within date range', function () {
    $product = $this->createBundleProduct([1000]);

    $option = $product->bundle_options->first();
    $optionSimple = $option->bundle_option_products->first()->product;

    $this->setSpecialPriceOnProduct($optionSimple, 700, now()->subDay()->format('Y-m-d'), now()->addMonth()->format('Y-m-d'));

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

it('should use regular price when bundle option product special price has expired', function () {
    $product = $this->createBundleProduct([1000]);

    $option = $product->bundle_options->first();
    $optionSimple = $option->bundle_option_products->first()->product;

    $this->setSpecialPriceOnProduct($optionSimple, 700, now()->subMonth()->format('Y-m-d'), now()->subDay()->format('Y-m-d'));

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should use regular price when bundle option product special price has not started', function () {
    $product = $this->createBundleProduct([1000]);

    $option = $product->bundle_options->first();
    $optionSimple = $option->bundle_option_products->first()->product;

    $this->setSpecialPriceOnProduct($optionSimple, 700, now()->addDay()->format('Y-m-d'), now()->addMonth()->format('Y-m-d'));

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 1000);
});

it('should apply special price to bundle option product when no date range is set', function () {
    $product = $this->createBundleProduct([1000]);

    $option = $product->bundle_options->first();
    $optionSimple = $option->bundle_option_products->first()->product;

    $this->setSpecialPriceOnProduct($optionSimple, 750);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 750);
});
