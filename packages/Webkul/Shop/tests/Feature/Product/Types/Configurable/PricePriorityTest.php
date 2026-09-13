<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should charge a configurable variant at its special price when it beats the catalog rule', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->setSpecialPriceOnProduct($product->variants->first(), 800);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should charge a configurable variant at the catalog rule price when it beats the special price', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->setSpecialPriceOnProduct($product->variants->first(), 800);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Group Price As Floor
// ============================================================================

it('should charge a configurable variant at its customer group price when it beats the special price', function () {
    $product = $this->createConfigurableProduct([1000]);

    $this->setSpecialPriceOnProduct($product->variants->first(), 800);

    $this->setCustomerGroupPrice($product->variants->first(), 1, 'fixed', 600);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should take a cart rule discount off the variant price of a configurable product', function () {
    $product = $this->createConfigurableProduct([800]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addConfigurableProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800)
        ->assertCartDiscount($response, 50);
});
