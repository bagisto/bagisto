<?php

use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Models\CartRule;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Product\Models\ProductCustomerGroupPrice;

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule for configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    // Set special price on variant.
    $variant->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $variant);

    // Catalog rule: 10% off → 900. MIN(800, 900) = 800.
    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when lower than regular price for configurable variant', function () {
    $product = $this->createConfigurableProduct([1000]);
    $variant = $product->variants->first();

    ProductCustomerGroupPrice::factory()->create([
        'qty' => 1,
        'value_type' => 'fixed',
        'value' => 600,
        'product_id' => $variant->id,
        'customer_group_id' => 1,
    ]);

    Event::dispatch('catalog.product.update.after', $variant);

    $response = $this->addProductToCart($product->id, 1, [
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should apply cart rule discount on top of variant price for configurable product', function () {
    $product = $this->createConfigurableProduct([800]);
    $variant = $product->variants->first();

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
        'selected_configurable_option' => $variant->id,
    ])->assertOk();

    $this->assertCartItemPrice($response, 800);
    $this->assertCartDiscount($response, 50);
});
