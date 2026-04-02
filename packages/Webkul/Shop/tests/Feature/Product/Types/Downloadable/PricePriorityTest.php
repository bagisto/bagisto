<?php

use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Models\CartRule;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Product\Models\ProductCustomerGroupPrice;

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

    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 20]);

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

    ProductCustomerGroupPrice::factory()->create([
        'qty' => 1,
        'value_type' => 'fixed',
        'value' => 250,
        'product_id' => $product->id,
        'customer_group_id' => 1,
    ]);

    Event::dispatch('catalog.product.update.after', $product);

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

    CartRule::factory()->afterCreating(function (CartRule $rule) {
        $rule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $rule->cart_rule_channels()->sync([1]);
    })->create([
        'status' => 1,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'coupon_type' => 0,
    ]);

    $response = $this->addProductToCart($product->id, 1, [
        'links' => $product->downloadable_links->pluck('id')->toArray(),
    ])->assertOk();

    $this->assertCartItemPrice($response, 350);
    $this->assertCartDiscount($response, 50);
});
