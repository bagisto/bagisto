<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should charge a simple product at its special price when it beats the catalog rule', function () {
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should charge a simple product at the catalog rule price when it beats the special price', function () {
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Group Price As Floor
// ============================================================================

it('should charge a simple product at its customer group price when it beats the special price', function () {
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
    ]);

    $this->setCustomerGroupPrice($product, 1, 'fixed', 600);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should take a cart rule discount off the special price of a simple product', function () {
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
    ]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800)
        ->assertCartDiscount($response, 50);
});
