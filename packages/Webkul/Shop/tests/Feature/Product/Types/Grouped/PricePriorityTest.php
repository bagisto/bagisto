<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should charge an associated product of a grouped product at its special price when it beats the catalog rule', function () {
    $product = $this->createGroupedProduct([1000]);

    $this->setSpecialPriceOnProduct($product->grouped_products->first()->associated_product, 800);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should charge an associated product of a grouped product at the catalog rule price when it beats the special price', function () {
    $product = $this->createGroupedProduct([1000]);

    $this->setSpecialPriceOnProduct($product->grouped_products->first()->associated_product, 800);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Group Price As Floor
// ============================================================================

it('should charge an associated product of a grouped product at its customer group price when it beats the special price', function () {
    $product = $this->createGroupedProduct([1000]);

    $associated = $product->grouped_products->first()->associated_product;

    $this->setSpecialPriceOnProduct($associated, 800);

    $this->setCustomerGroupPrice($associated, 1, 'fixed', 600);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should take a cart rule discount off the associated product price of a grouped product', function () {
    $product = $this->createGroupedProduct([800]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addGroupedProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800)
        ->assertCartDiscount($response, 50);
});
