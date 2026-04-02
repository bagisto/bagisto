<?php

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule for downloadable product', function () {
    $product = $this->createDownloadableProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ], [0]);

    $this->createCatalogRuleForPricing(['action_type' => 'by_percent', 'discount_amount' => 20], [1, 2, 3]);

    $response = $this->addProductToCart($product->id, 1, [
        'links' => $product->downloadable_links->pluck('id')->toArray(),
    ])->assertOk();

    $this->assertCartItemPrice($response, 350);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when lower than special price for downloadable product', function () {
    $product = $this->createDownloadableProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
    ], [0]);

    $this->setCustomerGroupPrice($product, 1, 'fixed', 250);

    $response = $this->addProductToCart($product->id, 1, [
        'links' => $product->downloadable_links->pluck('id')->toArray(),
    ])->assertOk();

    $this->assertCartItemPrice($response, 250);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should apply cart rule discount on top of special price for downloadable product', function () {
    $product = $this->createDownloadableProduct([
        'price' => ['float_value' => 500],
        'special_price' => ['float_value' => 350],
    ], [0]);

    $this->createCartRuleForPricing(['action_type' => 'by_fixed', 'discount_amount' => 50]);

    $response = $this->addProductToCart($product->id, 1, [
        'links' => $product->downloadable_links->pluck('id')->toArray(),
    ])->assertOk();

    $this->assertCartItemPrice($response, 350);
    $this->assertCartDiscount($response, 50);
});
