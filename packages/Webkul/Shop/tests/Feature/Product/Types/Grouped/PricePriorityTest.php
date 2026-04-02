<?php

use Illuminate\Support\Facades\Event;
use Webkul\CartRule\Models\CartRule;
use Webkul\CatalogRule\Models\CatalogRule;
use Webkul\Product\Models\ProductCustomerGroupPrice;

/**
 * Add a grouped product to cart for priority tests.
 */
function addGroupedToCartForPriority($test, $product)
{
    $product->load('grouped_products');

    $qty = [];

    foreach ($product->grouped_products as $gp) {
        $qty[$gp->associated_product_id] = 1;
    }

    return $test->addProductToCart($product->id, 1, ['qty' => $qty]);
}

// ============================================================================
// Special Price vs Catalog Rule
// ============================================================================

it('should use the lower of special price and catalog rule for grouped associated product', function () {
    $product = $this->createGroupedProduct([1000]);
    $associated = $product->grouped_products->first()->associated_product;

    // Set special price = 800 on associated product.
    $associated->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $associated);

    // Catalog rule: 10% off → 900. MIN(800, 900) = 800.
    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 10]);

    $response = addGroupedToCartForPriority($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 800, 0);
});

it('should use catalog rule when lower than special price for grouped associated product', function () {
    $product = $this->createGroupedProduct([1000]);
    $associated = $product->grouped_products->first()->associated_product;

    // Set special price = 800 on associated product.
    $associated->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    Event::dispatch('catalog.product.update.after', $associated);

    // Catalog rule: 30% off → 700. MIN(800, 700) = 700.
    CatalogRule::factory()
        ->withIndex([1], [1, 2, 3])
        ->create(['status' => 1, 'action_type' => 'by_percent', 'discount_amount' => 30]);

    $response = addGroupedToCartForPriority($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 700, 0);
});

// ============================================================================
// Group Price as Floor
// ============================================================================

it('should use group price when lower than special price for grouped associated product', function () {
    $product = $this->createGroupedProduct([1000]);
    $associated = $product->grouped_products->first()->associated_product;

    // Set special price = 800.
    $associated->attribute_values()
        ->where('attribute_id', $this->getAttributeMap()['special_price']->id)
        ->update(['float_value' => 800]);

    // Set group price = 600. MIN(800, 600) = 600.
    ProductCustomerGroupPrice::factory()->create([
        'qty' => 1,
        'value_type' => 'fixed',
        'value' => 600,
        'product_id' => $associated->id,
        'customer_group_id' => 1,
    ]);

    Event::dispatch('catalog.product.update.after', $associated);

    $response = addGroupedToCartForPriority($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 600, 0);
});

// ============================================================================
// Cart Rule Stacking
// ============================================================================

it('should apply cart rule discount on top of associated product price for grouped product', function () {
    $product = $this->createGroupedProduct([800]);

    CartRule::factory()->afterCreating(function (CartRule $rule) {
        $rule->cart_rule_customer_groups()->sync([1, 2, 3]);
        $rule->cart_rule_channels()->sync([1]);
    })->create([
        'status' => 1,
        'action_type' => 'by_fixed',
        'discount_amount' => 50,
        'coupon_type' => 0,
    ]);

    $response = addGroupedToCartForPriority($this, $product)->assertOk();

    $this->assertCartItemPrice($response, 800, 0);
    $this->assertCartDiscount($response, 50);
});
