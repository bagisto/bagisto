<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule for virtual product', function () {
    // Special = 350, Catalog rule = 400 (20% off 500). MIN(350, 400) = 350.
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350);
});

it('should use catalog rule when lower than special price for virtual product', function () {
    // Special = 350, Catalog rule = 250 (50% off 500). MIN(350, 250) = 250.
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 50], [1, 2, 3]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 250);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when lower than special price for virtual product', function () {
    // Special = 350, Group = 250. MIN(350, 250) = 250.
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
    ]);

    $this->setCustomerGroupPrice($product, 1, 'fixed', 250);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 250);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should apply cart rule discount on top of special price for virtual product', function () {
    // Special = 350, Cart rule = 50. Item price = 350, Discount = 50.
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
    ]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350);
    $this->assertCartDiscount($response, 50);
});
