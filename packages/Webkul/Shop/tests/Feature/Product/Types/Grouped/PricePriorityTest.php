<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule for grouped associated product', function () {
    $product = $this->createGroupedProduct([1000]);
    $associated = $product->grouped_products->first()->associated_product;

    // Set special price = 800 on associated product.
    $associated->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $associated);

    // Catalog rule: 10% off → 900. MIN(800, 900) = 800.
    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10], [1, 2, 3]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800, 0);
});

it('should use catalog rule when lower than special price for grouped associated product', function () {
    $product = $this->createGroupedProduct([1000]);
    $associated = $product->grouped_products->first()->associated_product;

    // Set special price = 800 on associated product.
    $associated->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $associated);

    // Catalog rule: 30% off → 700. MIN(800, 700) = 700.
    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30], [1, 2, 3]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700, 0);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when lower than special price for grouped associated product', function () {
    $product = $this->createGroupedProduct([1000]);
    $associated = $product->grouped_products->first()->associated_product;

    // Set special price = 800.
    $associated->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    // Set group price = 600. MIN(800, 600) = 600.
    $this->setCustomerGroupPrice($associated, 1, 'fixed', 600);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 600, 0);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should apply cart rule discount on top of associated product price for grouped product', function () {
    $product = $this->createGroupedProduct([800]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800, 0);
    $this->assertCartDiscount($response, 50);
});
