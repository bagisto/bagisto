<?php

use Webkul\Tax\Facades\Tax;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

/**
 * A tax category mapped to one rate per entry, each a country-wide rate for the US unless overridden.
 */
function taxCategoryWithRates(array $rates): TaxCategory
{
    $taxCategory = TaxCategory::factory()->create();

    foreach ($rates as $rate) {
        $taxRate = TaxRate::factory()->create(array_merge([
            'country' => 'US',
            'state' => '',
            'zip_code' => '*',
        ], $rate));

        TaxMap::factory()->create([
            'tax_category_id' => $taxCategory->id,
            'tax_rate_id' => $taxRate->id,
        ]);
    }

    return $taxCategory;
}

/**
 * The rate the tax category applies to an address, or null when none applies.
 */
function applicableRate(TaxCategory $taxCategory, array $address): ?TaxRate
{
    $applied = null;

    Tax::isTaxApplicableInCurrentAddress($taxCategory, (object) $address, function ($rate) use (&$applied) {
        $applied = $rate;
    });

    return $applied;
}

// ============================================================================
// Rate Matching
// ============================================================================

it('applies a country-wide rate anywhere in the country', function () {
    $taxCategory = taxCategoryWithRates([['tax_rate' => 10]]);

    expect(applicableRate($taxCategory, ['country' => 'US', 'state' => 'CA', 'postcode' => '90001']))
        ->tax_rate->toEqual(10);
});

it('applies nothing in another country', function () {
    $taxCategory = taxCategoryWithRates([['tax_rate' => 10]]);

    expect(applicableRate($taxCategory, ['country' => 'IN', 'state' => 'DL', 'postcode' => '110001']))->toBeNull();
});

it('applies nothing to an address without a country', function () {
    $taxCategory = taxCategoryWithRates([['tax_rate' => 10]]);

    expect(applicableRate($taxCategory, ['country' => null, 'state' => 'CA', 'postcode' => '90001']))->toBeNull();
});

it('prefers the rate of the state over the country-wide one', function () {
    $taxCategory = taxCategoryWithRates([
        ['tax_rate' => 5],
        ['tax_rate' => 18, 'state' => 'CA'],
    ]);

    expect(applicableRate($taxCategory, ['country' => 'US', 'state' => 'CA', 'postcode' => '90001']))->tax_rate->toEqual(18)
        ->and(applicableRate($taxCategory, ['country' => 'US', 'state' => 'NY', 'postcode' => '10001']))->tax_rate->toEqual(5);
});

it('matches a rate pinned to one zip code only at that zip code', function () {
    $taxCategory = taxCategoryWithRates([['tax_rate' => 7, 'zip_code' => '90001']]);

    expect(applicableRate($taxCategory, ['country' => 'US', 'state' => 'CA', 'postcode' => '90001']))->tax_rate->toEqual(7)
        ->and(applicableRate($taxCategory, ['country' => 'US', 'state' => 'CA', 'postcode' => '90002']))->toBeNull();
});

it('matches a rate pinned to a zip code range inside that range', function () {
    $taxCategory = taxCategoryWithRates([[
        'tax_rate' => 7,
        'is_zip' => 1,
        'zip_code' => null,
        'zip_from' => '10000',
        'zip_to' => '20000',
    ]]);

    expect(applicableRate($taxCategory, ['country' => 'US', 'state' => 'NY', 'postcode' => '15000']))->tax_rate->toEqual(7)
        ->and(applicableRate($taxCategory, ['country' => 'US', 'state' => 'NY', 'postcode' => '9000']))->toBeNull()
        ->and(applicableRate($taxCategory, ['country' => 'US', 'state' => 'NY', 'postcode' => '20001']))->toBeNull();
});

it('takes the highest rate when several rates apply', function () {
    $taxCategory = taxCategoryWithRates([['tax_rate' => 5], ['tax_rate' => 12]]);

    expect(applicableRate($taxCategory, ['country' => 'US', 'state' => 'CA', 'postcode' => '90001']))->tax_rate->toEqual(12);
});

// ============================================================================
// Totals
// ============================================================================

it('sums the tax of the items and the shipping by rate', function () {
    $cart = (object) [
        'items' => [
            (object) ['applied_tax_rate' => 'VAT', 'tax_percent' => 18, 'tax_amount' => 1.804, 'base_tax_amount' => 1.804],
            (object) ['applied_tax_rate' => 'VAT', 'tax_percent' => 18, 'tax_amount' => 1.802, 'base_tax_amount' => 1.802],
            (object) ['applied_tax_rate' => 'GST', 'tax_percent' => 5, 'tax_amount' => 0.5, 'base_tax_amount' => 0.5],
        ],
        'selected_shipping_rate' => (object) ['applied_tax_rate' => 'VAT', 'tax_percent' => 18, 'tax_amount' => 0.9, 'base_tax_amount' => 0.9],
    ];

    expect(Tax::getTaxRatesWithAmount($cart))->toBe([
        'VAT (18%)' => 4.51,
        'GST (5%)' => 0.5,
    ]);
});

it('leaves untaxed shipping out of the tax summary', function () {
    $cart = (object) [
        'items' => [
            (object) ['applied_tax_rate' => 'VAT', 'tax_percent' => 18, 'tax_amount' => 1.8, 'base_tax_amount' => 1.8],
        ],
        'selected_shipping_rate' => (object) ['applied_tax_rate' => null, 'tax_percent' => 0, 'tax_amount' => 0, 'base_tax_amount' => 0],
    ];

    expect(Tax::getTaxRatesWithAmount($cart))->toBe(['VAT (18%)' => 1.8]);
});

it('breaks the tax down per item on the discounted amount when tax follows the discount', function (string $applyTaxOn, float $taxableAmount) {
    $this->setConfig([
        'sales.taxes.calculation.product_prices' => 'excluding_tax',
        'sales.taxes.calculation.apply_tax_on' => $applyTaxOn,
    ]);

    $cart = (object) [
        'items' => [
            (object) [
                'name' => 'Running Shoe',
                'applied_tax_rate' => 'VAT',
                'tax_percent' => 10,
                'total' => 100,
                'base_total' => 100,
                'discount_amount' => 10,
                'base_discount_amount' => 10,
                'tax_amount' => 9,
                'base_tax_amount' => 9,
            ],
        ],
        'selected_shipping_rate' => null,
    ];

    expect(Tax::getTaxBreakdown($cart))->toBe([
        'VAT (10%)' => [
            'tax_amount' => 9.0,
            'items' => [
                ['name' => 'Running Shoe', 'tax_amount' => 9.0, 'taxable_amount' => $taxableAmount],
            ],
        ],
    ]);
})->with([
    'after the discount' => ['after_discount', 90.0],
    'before the discount' => ['before_discount', 100.0],
]);

// ============================================================================
// Settings
// ============================================================================

it('knows whether product and shipping prices include tax', function () {
    $this->setConfig([
        'sales.taxes.calculation.product_prices' => 'including_tax',
        'sales.taxes.calculation.shipping_prices' => 'excluding_tax',
    ]);

    expect(Tax::isInclusiveTaxProductPrices())->toBeTrue()
        ->and(Tax::isInclusiveTaxShippingPrices())->toBeFalse();
});

it('reads the default destination from the settings', function () {
    $this->setConfig([
        'sales.taxes.default_destination_calculation.country' => 'IN',
        'sales.taxes.default_destination_calculation.state' => 'DL',
        'sales.taxes.default_destination_calculation.post_code' => '110001',
    ]);

    expect(Tax::getDefaultAddress())
        ->country->toBe('IN')
        ->state->toBe('DL')
        ->postcode->toBe('110001');
});

it('falls back to the application country when no default destination is set', function () {
    $this->setConfig('sales.taxes.default_destination_calculation.country', '');

    config(['app.default_country' => 'de']);

    expect(Tax::getDefaultAddress()->country)->toBe('DE');
});

it('reads the shipping origin from the settings', function () {
    $this->setConfig([
        'sales.shipping.origin.country' => 'US',
        'sales.shipping.origin.state' => 'CA',
        'sales.shipping.origin.zipcode' => '90001',
    ]);

    expect(Tax::getShippingOriginAddress())
        ->country->toBe('US')
        ->state->toBe('CA')
        ->postcode->toBe('90001');
});
