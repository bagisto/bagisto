<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\CartItem;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

/**
 * Tax everything at the given rate from the shipping origin, so the rate needs no cart address.
 */
function taxCategoryChargingAtShippingOrigin(float $rate): TaxCategory
{
    test()->setConfig([
        'sales.taxes.calculation.based_on' => 'shipping_origin',
        'sales.shipping.origin.country' => 'US',
        'sales.shipping.origin.state' => '',
        'sales.shipping.origin.zipcode' => '12345',
    ]);

    $taxRate = TaxRate::factory()->create([
        'country' => 'US',
        'state' => '',
        'zip_code' => '*',
        'tax_rate' => $rate,
    ]);

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id' => $taxRate->id,
    ]);

    return $taxCategory;
}

/**
 * A 100.00 product taxed under the given category.
 */
function productTaxedUnder(TaxCategory $taxCategory)
{
    return test()->createSimpleProduct([
        'price' => ['float_value' => 100],
        'tax_category_id' => ['integer_value' => $taxCategory->id, 'channel' => core()->getCurrentChannelCode()],
    ]);
}

// ============================================================================
// Tax On Items Written One After Another In The Same Request
// ============================================================================

it('should tax a product it has just written beside the one the cart already held', function () {
    $taxCategory = taxCategoryChargingAtShippingOrigin(10);

    $first = productTaxedUnder($taxCategory);

    $second = productTaxedUnder($taxCategory);

    Cart::addProduct($first, ['product_id' => $first->id, 'quantity' => 1]);

    Cart::addProduct($second, ['product_id' => $second->id, 'quantity' => 1]);

    $cart = Cart::getCart();

    $taxAmounts = CartItem::query()
        ->where('cart_id', $cart->id)
        ->pluck('tax_amount', 'product_id')
        ->map(fn ($amount) => (float) $amount);

    expect($taxAmounts[$first->id])->toBePrice(10.0)
        ->and($taxAmounts[$second->id])->toBePrice(10.0)
        ->and($cart->tax_total)->toBePrice(20.0);
});

// ============================================================================
// Tax Left Behind By A Category That Has Gone
// ============================================================================

it('should take the tax off an item once the category it was taxed under has been deleted', function () {
    $taxCategory = taxCategoryChargingAtShippingOrigin(10);

    $product = productTaxedUnder($taxCategory);

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    expect((float) Cart::getCart()->tax_total)->toBe(10.0);

    $taxCategory->delete();

    Cart::collectTotals();

    $item = CartItem::query()->where('cart_id', Cart::getCart()->id)->firstOrFail();

    expect($item->tax_amount)->toBePrice(0.0)
        ->and($item->applied_tax_rate)->toBeNull()
        ->and($item->total_incl_tax)->toBePrice((float) $item->total)
        ->and(Cart::getCart()->tax_total)->toBePrice(0.0);
});

it('should fall back to the store tax category when the item category it was taxed under is gone', function () {
    $gone = taxCategoryChargingAtShippingOrigin(10);

    $product = productTaxedUnder($gone);

    $fallback = taxCategoryChargingAtShippingOrigin(5);

    $this->setConfig('sales.taxes.categories.product', $fallback->id);

    Cart::addProduct($product, ['product_id' => $product->id, 'quantity' => 1]);

    expect(Cart::getCart()->tax_total)->toBePrice(10.0);

    $gone->delete();

    Cart::collectTotals();

    $item = CartItem::query()->where('cart_id', Cart::getCart()->id)->firstOrFail();

    expect($item->tax_amount)->toBePrice(5.0)
        ->and(Cart::getCart()->tax_total)->toBePrice(5.0);
});
