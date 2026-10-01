<?php

use Webkul\Product\Models\ProductCustomerGroupPrice;

use function Pest\Laravel\get;

/**
 * Offer the product at the given price for the given quantity, to every customer group.
 */
function offerGroupPrice(int $productId, float $value, int $qty = 2): void
{
    ProductCustomerGroupPrice::factory()->create([
        'qty' => $qty,
        'value_type' => 'fixed',
        'value' => $value,
        'product_id' => $productId,
        'customer_group_id' => 1,
    ]);
}

// ============================================================================
// Customer Group Pricing Offers
// ============================================================================

it('should show the product page when the price and the group price are both zero', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 0]]);

    offerGroupPrice($product->id, 0);

    get(route('shop.product_or_category.index', $product->url_key))->assertOk();
});

it('should offer no discount on a free product', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 0]]);

    offerGroupPrice($product->id, 0);

    $offers = $product->getTypeInstance()->getCustomerGroupPricingOffers();

    expect($offers)->toHaveCount(1)
        ->and($offers[0])->toContain('0.00%');
});

it('should still work out the discount on a product that costs something', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    offerGroupPrice($product->id, 75);

    $offers = $product->getTypeInstance()->getCustomerGroupPricingOffers();

    expect($offers[0])->toContain('25.00%');
});
