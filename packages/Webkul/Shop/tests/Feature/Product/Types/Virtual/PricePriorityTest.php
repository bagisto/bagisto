<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should charge a virtual product at its special price when it beats the catalog rule', function () {
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350);
});

it('should charge a virtual product at the catalog rule price when it beats the special price', function () {
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 50]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 250);
});

// ============================================================================
// Group Price As Floor
// ============================================================================

it('should charge a virtual product at its customer group price when it beats the special price', function () {
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

it('should take a cart rule discount off the special price of a virtual product', function () {
    $product = $this->createVirtualProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
    ]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 350)
        ->assertCartDiscount($response, 50);
});
