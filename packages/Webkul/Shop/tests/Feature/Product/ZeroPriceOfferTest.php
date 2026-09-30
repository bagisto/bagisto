<?php

use function Pest\Laravel\get;

// ============================================================================
// Customer Group Pricing Offers
// ============================================================================

it('should show the product page when the price and the group price are both zero', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 0]]);

    $this->setCustomerGroupPrice($product, 1, 'fixed', 0, 2);

    get(route('shop.product_or_category.index', $product->url_key))->assertOk();
});

it('should offer no discount on a free product', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 0]]);

    $this->setCustomerGroupPrice($product, 1, 'fixed', 0, 2);

    $offers = $product->getTypeInstance()->getCustomerGroupPricingOffers();

    expect($offers)->toHaveCount(1)
        ->and($offers[0])->toContain('0.00%');
});

it('should still work out the discount on a product that costs something', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $this->setCustomerGroupPrice($product, 1, 'fixed', 75, 2);

    $offers = $product->getTypeInstance()->getCustomerGroupPricingOffers();

    expect($offers[0])->toContain('25.00%');
});
