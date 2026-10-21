<?php

use Webkul\Checkout\Facades\Cart;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Checkout\Models\CartAddress;
use Webkul\Checkout\Models\CartItem;
use Webkul\Checkout\Models\CartShippingRate;
use Webkul\Customer\Models\Customer;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;

/**
 * One tax category charging a different rate in each country, so the rate a total is taxed at
 * says which address the tax was calculated against.
 */
function taxCategoryCharging(array $ratesByCountry): TaxCategory
{
    $taxCategory = TaxCategory::factory()->create();

    foreach ($ratesByCountry as $country => $rate) {
        $taxRate = TaxRate::factory()->create([
            'country' => $country,
            'state' => '',
            'zip_code' => '*',
            'tax_rate' => $rate,
        ]);

        TaxMap::factory()->create([
            'tax_category_id' => $taxCategory->id,
            'tax_rate_id' => $taxRate->id,
        ]);
    }

    return $taxCategory;
}

/**
 * A cart of one 100.00 product billed to India and shipped to the United States, so the two
 * addresses can never be confused for one another.
 */
function cartBilledToIndiaShippedToUs(string $productType, TaxCategory $taxCategory): CartModel
{
    $product = $productType === 'virtual'
        ? test()->createVirtualProduct([
            'price' => ['float_value' => 100],
            'tax_category_id' => ['integer_value' => $taxCategory->id, 'channel' => core()->getCurrentChannelCode()],
        ])
        : test()->createSimpleProduct([
            'price' => ['float_value' => 100],
            'tax_category_id' => ['integer_value' => $taxCategory->id, 'channel' => core()->getCurrentChannelCode()],
        ]);

    $customer = Customer::factory()->create();

    $cart = CartModel::factory()->create([
        'customer_id' => $customer->id,
        'customer_email' => $customer->email,
        'is_guest' => false,
        'shipping_method' => 'flatrate_flatrate',
    ]);

    CartItem::factory()->create([
        'cart_id' => $cart->id,
        'product_id' => $product->id,
        'sku' => $product->sku,
        'quantity' => 1,
        'name' => $product->name,
        'price' => 100,
        'price_incl_tax' => 100,
        'base_price' => 100,
        'base_price_incl_tax' => 100,
        'total' => 100,
        'total_incl_tax' => 100,
        'base_total' => 100,
        'base_total_incl_tax' => 100,
        'type' => $product->type,
        'additional' => ['product_id' => $product->id, 'quantity' => 1],
    ]);

    CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'customer_id' => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_BILLING,
        'country' => 'IN',
        'state' => 'DL',
        'postcode' => '110001',
    ]);

    $shippingAddress = CartAddress::factory()->create([
        'cart_id' => $cart->id,
        'customer_id' => $customer->id,
        'address_type' => CartAddress::ADDRESS_TYPE_SHIPPING,
        'country' => 'US',
        'state' => 'CA',
        'postcode' => '90001',
    ]);

    CartShippingRate::factory()->create([
        'cart_id' => $cart->id,
        'cart_address_id' => $shippingAddress->id,
        'carrier' => 'flatrate',
        'carrier_title' => 'Flat Rate',
        'method' => 'flatrate_flatrate',
        'method_title' => 'Flat Rate',
        'method_description' => 'Flat Rate',
        'price' => 50,
        'base_price' => 50,
        'price_incl_tax' => 50,
        'base_price_incl_tax' => 50,
    ]);

    Cart::setCart($cart);

    return $cart->fresh();
}

/**
 * The tax the cart's single item was charged once the totals were collected.
 */
function itemTaxAfterCollecting(): float
{
    Cart::collectTotals();

    return (float) Cart::getCart()->items->first()->tax_amount;
}

// ============================================================================
// An Item Is Taxed Against The Address The Store Is Configured To Use
// ============================================================================

it('should tax a stockable item against the shipping address', function () {
    $this->setConfig('sales.taxes.calculation.based_on', 'shipping_address');

    cartBilledToIndiaShippedToUs('simple', taxCategoryCharging(['US' => 10, 'IN' => 20]));

    expect(itemTaxAfterCollecting())->toBe(10.0);
});

it('should tax an item against the billing address when the store is set to bill', function () {
    $this->setConfig('sales.taxes.calculation.based_on', 'billing_address');

    cartBilledToIndiaShippedToUs('simple', taxCategoryCharging(['US' => 10, 'IN' => 20]));

    expect(itemTaxAfterCollecting())->toBe(20.0);
});

it('should tax a non-stockable item against the billing address even when the store ships', function () {
    $this->setConfig('sales.taxes.calculation.based_on', 'shipping_address');

    cartBilledToIndiaShippedToUs('virtual', taxCategoryCharging(['US' => 10, 'IN' => 20]));

    expect(itemTaxAfterCollecting())->toBe(20.0);
});

// ============================================================================
// A Shipping Tax Category That No Longer Exists Is Survivable
// ============================================================================

it('should collect totals when the configured shipping tax category has been deleted', function () {
    $taxCategory = taxCategoryCharging(['US' => 10, 'IN' => 20]);

    $this->setConfig([
        'sales.taxes.calculation.based_on' => 'shipping_address',
        'sales.taxes.categories.shipping' => $taxCategory->id + 1000,
    ]);

    cartBilledToIndiaShippedToUs('simple', $taxCategory);

    Cart::collectTotals();

    expect((float) Cart::getCart()->selected_shipping_rate->tax_amount)->toBe(0.0);
});

// ============================================================================
// Shipping Is Taxed Against That Same Configured Address
// ============================================================================

it('should tax shipping against the shipping address', function () {
    $taxCategory = taxCategoryCharging(['US' => 10, 'IN' => 20]);

    $this->setConfig([
        'sales.taxes.calculation.based_on' => 'shipping_address',
        'sales.taxes.categories.shipping' => $taxCategory->id,
    ]);

    cartBilledToIndiaShippedToUs('simple', $taxCategory);

    Cart::collectTotals();

    expect((float) Cart::getCart()->selected_shipping_rate->tax_amount)->toBe(5.0);
});

it('should tax shipping against the billing address when the store is set to bill', function () {
    $taxCategory = taxCategoryCharging(['US' => 10, 'IN' => 20]);

    $this->setConfig([
        'sales.taxes.calculation.based_on' => 'billing_address',
        'sales.taxes.categories.shipping' => $taxCategory->id,
    ]);

    cartBilledToIndiaShippedToUs('simple', $taxCategory);

    Cart::collectTotals();

    expect((float) Cart::getCart()->selected_shipping_rate->tax_amount)->toBe(10.0);
});
