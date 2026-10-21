<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\postJson;

/**
 * Tax everything shipped to the US at the given rate, for shipping and for products alike.
 */
function taxShippingAndProductsToUs(float $rate): void
{
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

    test()->setConfig([
        'sales.taxes.calculation.based_on' => 'shipping_address',
        'sales.taxes.categories.shipping' => $taxCategory->id,
    ]);
}

/**
 * A cart of one product shipped to a US address by flat rate, under the given shipping tax setting.
 */
function cartWithTaxedFlatRateShipping(string $shippingPrices)
{
    test()->setConfig('sales.taxes.calculation.shipping_prices', $shippingPrices);

    taxShippingAndProductsToUs(10);

    $product = test()->createSimpleProduct(['price' => ['float_value' => 100]]);

    test()->loginAsCustomer();

    test()->addProductToCart($product->id);

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => test()->storefrontAddress(['use_for_shipping' => true]),
    ])->assertOk();

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => 'flatrate_flatrate',
    ])->assertOk();

    Cart::collectTotals();

    return Cart::getCart()->selected_shipping_rate;
}

// ============================================================================
// Shipping Tax Is Broken Out Of Or Added To The Rate
// ============================================================================

it('should add the tax on top of an excluding-tax shipping rate', function () {
    $rate = cartWithTaxedFlatRateShipping('excluding_tax');

    expect((float) $rate->price)->toBe(10.0)
        ->and((float) $rate->tax_amount)->toBe(1.0)
        ->and((float) $rate->price_incl_tax)->toBe(11.0);
});

it('should break the tax out of an including-tax shipping rate', function () {
    $rate = cartWithTaxedFlatRateShipping('including_tax');

    expect((float) $rate->price_incl_tax)->toBe(10.0)
        ->and(round((float) $rate->tax_amount, 2))->toBe(0.91)
        ->and(round((float) $rate->price, 2))->toBe(9.09);
});

// ============================================================================
// A Custom Price Is What The Item Is Repriced To
// ============================================================================

it('should reprice an item to its custom price when the totals are collected', function () {
    $product = $this->createSimpleProduct(['price' => ['float_value' => 100]]);

    $this->loginAsCustomer();

    $this->addProductToCart($product->id);

    $item = Cart::getCart()->items->first();

    expect((float) $item->price)->toBe(100.0);

    $item->custom_price = 42;

    $item->save();

    Cart::collectTotals();

    expect((float) Cart::getCart()->items->first()->price)->toBe(42.0);
});
