<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule for configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    // Set special price on variant.
    $variant->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $variant);

    // Catalog rule: 10% off → 900. MIN(800, 900) = 800.
    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10], [1, 2, 3]);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when lower than regular price for configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    $this->setCustomerGroupPrice($variant, 1, 'fixed', 600);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should apply cart rule discount on top of variant price for configurable product', function () {
    $product = $this->createConfigurableProduct([800]);
    $variant = $product->variants->first();

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
    $this->assertCartDiscount($response, 50);
});
