<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\CoreConfig;
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

    setTaxConfig('sales.taxes.categories.shipping', $taxCategory->id);

    setTaxConfig('sales.taxes.calculation.based_on', 'shipping_address');
}

/**
 * Write a configuration value, replacing whatever is already stored under that code.
 */
function setTaxConfig(string $code, $value): void
{
    CoreConfig::query()->where('code', $code)->delete();

    CoreConfig::factory()->create(['code' => $code, 'value' => $value]);
}

/**
 * A cart of one product shipped to a US address by flat rate, under the given shipping tax setting.
 */
function cartWithTaxedFlatRateShipping(string $shippingPrices)
{
    setTaxConfig('sales.taxes.calculation.shipping_prices', $shippingPrices);

    taxShippingAndProductsToUs(10);

    $product = test()->createSimpleProduct(['price' => ['float_value' => 100]]);

    test()->loginAsCustomer();

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ])->assertOk();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->safeEmail(),
            'address' => [fake()->streetAddress()],
            'city' => fake()->city(),
            'country' => 'US',
            'state' => 'CA',
            'postcode' => '90001',
            'phone' => fake()->e164PhoneNumber(),
            'use_for_shipping' => 1,
        ],
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

    postJson(route('shop.api.checkout.cart.store'), [
        'product_id' => $product->id,
        'quantity' => 1,
    ])->assertOk();

    $item = Cart::getCart()->items->first();

    expect((float) $item->price)->toBe(100.0);

    $item->custom_price = 42;

    $item->save();

    Cart::collectTotals();

    expect((float) Cart::getCart()->items->first()->price)->toBe(42.0);
});
