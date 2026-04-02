<?php

use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Models\CartRule;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Product\Models\ProductCustomerGroupPrice;

/**
 * Add a bundle product's first option to cart for priority tests.
 */
function addBundleToCartForPriority($test, $product)
{
    $product->load('bundle_options.bundle_option_products');

    $option = $product->bundle_options->first();
    $firstOptionProduct = $option->bundle_option_products->first();

    return $test->addProductToCart($product->id, 1, [
        'bundle_options' => [$option->id => [$firstOptionProduct->id]],
        'bundle_option_qty' => [$option->id => 1],
    ]);
}

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule for bundle option product', function () {
    $product = $this->createBundleProduct([1000]);
    $optionSimple = $product->bundle_options->first()->bundle_option_products->first()->product;

    // Set special price = 800.
    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    // Catalog rule: 10% off → 900. MIN(800, 900) = 800.
    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = addBundleToCartForPriority($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 800);
});

it('should use catalog rule when lower than special price for bundle option product', function () {
    $product = $this->createBundleProduct([1000]);
    $optionSimple = $product->bundle_options->first()->bundle_option_products->first()->product;

    // Set special price = 800.
    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    // Catalog rule: 30% off → 700. MIN(800, 700) = 700.
    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 30]);

    $response = addBundleToCartForPriority($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 700);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when lower than special price for bundle option product', function () {
    $product = $this->createBundleProduct([1000]);
    $optionSimple = $product->bundle_options->first()->bundle_option_products->first()->product;

    // Set special price = 800.
    $optionSimple->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    // Group price = 600. MIN(800, 600) = 600.
    ProductCustomerGroupPrice::factory()->create([
        'qty' => 1,
        'value_type' => 'fixed',
        'value' => 600,
        'product_id' => $optionSimple->id,
        'customer_group_id' => 1,
    ]);

    Event::dispatch('catalog.product.update.after', $optionSimple);

    $response = addBundleToCartForPriority($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 600);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should apply cart rule discount on top of bundle option product price', function () {
    $product = $this->createBundleProduct([800]);

    CartRule::factory()->afterCreating(function (CartRule $rule) {
        $rule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $rule->cart_rule_channels()->sync([1]);
    })->create([
        'status' => 1,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'coupon_type' => 0,
    ]);

    $response = addBundleToCartForPriority($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 800);
    $this->assertCartDiscount($response, 50);
});
