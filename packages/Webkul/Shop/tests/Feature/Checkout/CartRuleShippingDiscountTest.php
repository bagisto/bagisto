<?php

use Illuminate\Testing\TestResponse;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\postJson;

/**
 * Put one unit of a product in the cart, ship it to the default storefront address with the
 * given shipping method, and return the cart the shipping step left behind.
 */
function cartShippedWith(string $shippingMethod): TestResponse
{
    $product = test()->createSimpleProduct();

    test()->addProductToCart($product->id)->assertOk();

    postJson(route('shop.checkout.onepage.addresses.store'), [
        'billing' => array_merge(test()->storefrontAddress(), ['use_for_shipping' => true]),
    ])->assertOk();

    postJson(route('shop.checkout.onepage.shipping_methods.store'), [
        'shipping_method' => $shippingMethod,
    ])->assertOk();

    return test()->getJson(route('shop.api.checkout.cart.index'))->assertOk();
}

/**
 * Tax shipping to the US at the given rate.
 */
function taxShippingToUs(float $rate): void
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

    test()->setConfig('sales.taxes.categories.shipping', $taxCategory->id);
}

// ============================================================================
// Shipping Discount
// ============================================================================

it('should keep the discount of a cart rule that applies to shipping', function (bool $taxedShipping) {
    if ($taxedShipping) {
        taxShippingToUs(10);
    }

    $this->createCartRuleForPricing([
        'action_type' => 'by_percent',
        'discount_amount' => 50,
        'apply_to_shipping' => 1,
        'free_shipping' => 0,
    ]);

    $response = cartShippedWith('flatrate_flatrate');

    $this->assertDatabaseHas('cart_shipping_rates', [
        'cart_id' => $response->json('data.id'),
        'method' => 'flatrate_flatrate',
        'discount_amount' => 5,
        'base_discount_amount' => 5,
    ]);
})->with([
    'untaxed shipping' => [false],
    'taxed shipping' => [true],
]);

it('should leave no shipping discount once a cart rule makes shipping free', function (bool $taxedShipping) {
    if ($taxedShipping) {
        taxShippingToUs(10);
    }

    $this->createCartRuleForPricing([
        'action_type' => 'by_percent',
        'discount_amount' => 50,
        'apply_to_shipping' => 1,
        'free_shipping' => 1,
    ]);

    $response = cartShippedWith('flatrate_flatrate');

    $this->assertDatabaseHas('cart_shipping_rates', [
        'cart_id' => $response->json('data.id'),
        'method' => 'flatrate_flatrate',
        'price' => 0,
        'discount_amount' => 0,
        'base_discount_amount' => 0,
    ]);
})->with([
    'untaxed shipping' => [false],
    'taxed shipping' => [true],
]);
