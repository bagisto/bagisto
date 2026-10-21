<?php

use Illuminate\Testing\TestResponse;
use Webkul\Core\Models\CoreConfig;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\postJson;

/**
 * Put one unit of a product in the cart, ship it to a US address with the given shipping
 * method, and return the cart the shipping step left behind.
 */
function cartShippedWith(string $shippingMethod): TestResponse
{
    $product = test()->createSimpleProduct();

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

    CoreConfig::factory()->create([
        'code' => 'sales.taxes.categories.shipping',
        'value' => $taxCategory->id,
    ]);
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
