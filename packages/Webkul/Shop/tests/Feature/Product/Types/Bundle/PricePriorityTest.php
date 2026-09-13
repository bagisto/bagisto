<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should charge an option product of a bundle product at its special price when it beats the catalog rule', function () {
    $product = $this->createBundleProduct([1000]);

    $this->setSpecialPriceOnProduct($product->bundle_options->first()->bundle_option_products->first()->product, 800);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should charge an option product of a bundle product at the catalog rule price when it beats the special price', function () {
    $product = $this->createBundleProduct([1000]);

    $this->setSpecialPriceOnProduct($product->bundle_options->first()->bundle_option_products->first()->product, 800);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 30]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Group Price As Floor
// ============================================================================

it('should charge an option product of a bundle product at its customer group price when it beats the special price', function () {
    $product = $this->createBundleProduct([1000]);

    $optionProduct = $product->bundle_options->first()->bundle_option_products->first()->product;

    $this->setSpecialPriceOnProduct($optionProduct, 800);

    $this->setCustomerGroupPrice($optionProduct, 1, 'fixed', 600);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should take a cart rule discount off the option product price of a bundle product', function () {
    $product = $this->createBundleProduct([800]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addBundleProductToCart($product)->assertOk();

    $this->assertCartItemPrice($response, 800)
        ->assertCartDiscount($response, 50);
});
