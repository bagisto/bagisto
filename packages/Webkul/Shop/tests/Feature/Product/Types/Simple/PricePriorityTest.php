<?php

use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Models\CartRule;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Product\Models\ProductCustomerGroupPrice;

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule price', function () {
    // Special price = 800, Catalog rule makes it 900 (10% off 1000).
    // MIN(800, 900) = 800 → special price wins.
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should use catalog rule when it gives a lower price than special price', function () {
    // Special price = 800, Catalog rule makes it 700 (30% off 1000).
    // MIN(800, 700) = 700 → catalog rule wins.
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
        'special_price_from' => ['date_value' => now()->subDay()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
        'special_price_to' => ['date_value' => now()->addMonth()->format('Y-m-d'), 'channel' => core()->getCurrentChannelCode()],
    ]);

    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 30]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when it is lower than special price', function () {
    // Special price = 800, Group price = 600.
    // MIN(800, 600) = 600 → group price wins.
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
    ]);

    ProductCustomerGroupPrice::factory()->create([
        'qty' => 1,
        'value_type' => 'fixed',
        'value' => 600,
        'product_id' => $product->id,
        'customer_group_id' => 1,
    ]);

    // Re-index so the price index picks up the group price.
    Event::dispatch('catalog.product.update.after', $product);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking on Product Discount
// ============================================================================

it('should apply cart rule discount on top of special price', function () {
    // Special price = 800, Cart rule = 50 fixed discount.
    // Item price = 800, Discount = 50, Grand total = 750.
    $product = $this->createSimpleProduct([
        'price' => ['float_value' => 1000],
        'special_price' => ['float_value' => 800],
    ]);

    CartRule::factory()->afterCreating(function (CartRule $rule) {
        $rule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $rule->cart_rule_channels()->sync([1]);
    })->create([
        'status' => 1,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'coupon_type' => 0,
    ]);

    $response = $this->addProductToCart($product->id)->assertOk();

    $this->assertCartItemPrice($response, 800);
    $this->assertCartDiscount($response, 50);
});
