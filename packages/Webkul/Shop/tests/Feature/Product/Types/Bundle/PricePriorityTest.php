<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule for bundle option product', function () {
    $product = $this->createBundleProduct([1000]);
    $optionSimple = $product->bundle_options->first()->bundle_option_products->first()->product;

    // Set special price = 800.
    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    // Catalog rule: 10% off → 900. MIN(800, 900) = 800.
    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10], [1, 2, 3]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should use catalog rule when lower than special price for bundle option product', function () {
    $product = $this->createBundleProduct([1000]);
    $optionSimple = $product->bundle_options->first()->bundle_option_products->first()->product;

    // Set special price = 800.
    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    // Catalog rule: 30% off → 700. MIN(800, 700) = 700.
    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30], [1, 2, 3]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when lower than special price for bundle option product', function () {
    $product = $this->createBundleProduct([1000]);
    $optionSimple = $product->bundle_options->first()->bundle_option_products->first()->product;

    // Set special price = 800.
    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    // Group price = 600. MIN(800, 600) = 600.
    $this->setCustomerGroupPrice($optionSimple, 1, 'fixed', 600);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should apply cart rule discount on top of bundle option product price', function () {
    $product = $this->createBundleProduct([800]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
    $this->assertCartDiscount($response, 50);
});
