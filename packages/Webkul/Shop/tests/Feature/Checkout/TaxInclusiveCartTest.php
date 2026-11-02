<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Core\Models\CoreConfig;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;

/**
 * Write a configuration value, replacing whatever is already stored under that code.
 */
function writeTaxSetting(string $code, $value): void
{
    CoreConfig::query()->where('code', $code)->delete();

    CoreConfig::factory()->create(['code' => $code, 'value' => $value]);
}

/**
 * A customer's cart of the given number of products whose prices include a 10% tax on US addresses.
 */
function cartTaxedInclusively(int $products): void
{
    writeTaxSetting('sales.taxes.calculation.product_prices', 'including_tax');

    writeTaxSetting('sales.taxes.calculation.based_on', 'shipping_address');

    $taxCategory = TaxCategory::factory()->create();

    TaxMap::factory()->create([
        'tax_category_id' => $taxCategory->id,
        'tax_rate_id' => TaxRate::factory()->create([
            'country' => 'US',
            'state' => '',
            'zip_code' => '*',
            'tax_rate' => 10,
        ])->id,
    ]);

    writeTaxSetting('sales.taxes.categories.product', $taxCategory->id);

    test()->loginAsCustomer();

    foreach (range(1, $products) as $product) {
        postJson(route('shop.api.checkout.cart.store'), [
            'product_id' => test()->createSimpleProduct(['price' => ['float_value' => 110]])->id,
            'quantity' => 1,
        ])->assertOk();
    }

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
}

// ============================================================================
// Removing An Item From A Cart Whose Prices Include Tax
// ============================================================================

it('should remove a taxed item from a cart whose product prices include tax', function (int $products) {
    cartTaxedInclusively($products);

    $item = Cart::getCart()->items()->first();

    expect((float) $item->tax_amount)->toBeGreaterThan(0.0);

    deleteJson(route('shop.api.checkout.cart.destroy'), ['cart_item_id' => $item->id])
        ->assertOk()
        ->assertJsonCount($products - 1, 'data.items');
})->with([
    'the only item' => [1],
    'one of two items' => [2],
]);
