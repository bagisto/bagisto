<?php

namespace Tests\Functional\Shop;

use Cart;
use FunctionalTester;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Customer\Models\Customer;
use Illuminate\Support\Facades\Config;
use Helper\Bagisto;
use Webkul\Customer\Models\CustomerAddress;

class CartTaxesCest
{
    public $country;

    private const CART_TOTAL_PRECISION = 2;
    private const TAX_AMOUNT_PRECISION = 2;

    function _before(FunctionalTester $I)
    {
        $this->country = strtoupper(Config::get('app.default_country')) ?? 'DE';
    }

    public function checkCartWithMultipleTaxRates(FunctionalTester $I): void
    {
        $tax1 = $I->have(TaxRate::class, [
            'country' => $this->country,
        ]);
        $taxCategory1 = $I->have(TaxCategory::class);
        $I->have(TaxMap::class, [
            'tax_rate_id'     => $tax1->id,
            'tax_category_id' => $taxCategory1->id,
        ]);

        $tax2 = $I->have(TaxRate::class, [
            'country' => $this->country,
        ]);
        $taxCategory2 = $I->have(TaxCategory::class);
        $I->have(TaxMap::class, [
            'tax_rate_id'     => $tax2->id,
            'tax_category_id' => $taxCategory2->id,
        ]);

        $config1 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategory1->id,
            ],
        ];
        $product1 = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, $config1);

        $config2 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategory2->id,
            ],
        ];
        $product2 = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, $config2);

        $prod1Quantity = $I->fake()->numberBetween(9, 30);
        // quantity of product1 should be odd
        if ($prod1Quantity % 2 !== 0) {
            $prod1Quantity -= 1;
        }

        $prod2Quantity = $I->fake()->numberBetween(9, 30);
        // quantity of product2 should be even
        if ($prod2Quantity % 2 == 0) {
            $prod2Quantity -= 1;
        }

        Cart::addProduct($product1->id, [
            '_token'     => session('_token'),
            'product_id' => $product1->id,
            'quantity'   => 1,
        ]);

        $expectedTaxAmount1 = round(
            round(1 * $product1->price, self::CART_TOTAL_PRECISION)
                * $tax1->tax_rate / 100,
            self::TAX_AMOUNT_PRECISION
        );

        $I->amOnPage('/checkout/cart');
        $I->seeInSource('Tax ' . $tax1->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax1->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount1), '#basetaxamount-' . core()->taxRateAsIdentifier($tax1->tax_rate));

        Cart::addProduct($product1->id, [
            '_token'     => session('_token'),
            'product_id' => $product1->id,
            'quantity'   => $prod1Quantity,
        ]);

        $expectedTaxAmount1 = round(
            round((1 + $prod1Quantity) * $product1->price, self::CART_TOTAL_PRECISION)
                * $tax1->tax_rate / 100,
            self::TAX_AMOUNT_PRECISION
        );

        $I->amOnPage('/checkout/cart');
        $I->seeInSource('Tax ' . $tax1->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax1->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount1), '#basetaxamount-' . core()->taxRateAsIdentifier($tax1->tax_rate));

        Cart::addProduct($product2->id, [
            '_token'     => session('_token'),
            'product_id' => $product2->id,
            'quantity'   => $prod2Quantity,
        ]);

        $expectedTaxAmount2 = round(
            round($prod2Quantity * $product2->price, self::CART_TOTAL_PRECISION)
                * $tax2->tax_rate / 100,
            self::TAX_AMOUNT_PRECISION
        );

        $I->amOnPage('/checkout/cart');
        $I->seeInSource('Tax ' . $tax1->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax1->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount1), '#basetaxamount-' . core()->taxRateAsIdentifier($tax1->tax_rate));

        $I->seeInSource('Tax ' . $tax2->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax2->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount2), '#basetaxamount-' . core()->taxRateAsIdentifier($tax2->tax_rate));
        $cart = Cart::getCart();

        $I->assertEquals(2, $cart->items_count);
        $I->assertEquals((float)($prod1Quantity + 1 + $prod2Quantity), $cart->items_qty);
        $I->assertEqualsWithDelta(round($expectedTaxAmount1 + $expectedTaxAmount2, self::TAX_AMOUNT_PRECISION), $cart->tax_total, 0.001);

        Cart::removeItem($cart->items[1]->id);

        $I->amOnPage('/checkout/cart');
        $I->amOnPage('/checkout/cart');
        $I->seeInSource('Tax ' . $tax1->tax_rate . ' %');
        $I->seeInSource(core()->currency($expectedTaxAmount1));

        $I->dontSeeInSource('Tax ' . $tax2->tax_rate . ' %');
        $I->dontSeeInSource(core()->currency($expectedTaxAmount2));

        $cart = Cart::getCart();

        $I->assertEquals(1, $cart->items_count);
        $I->assertEquals((float)($prod1Quantity + 1), $cart->items_qty);
        $I->assertEqualsWithDelta($expectedTaxAmount1, $cart->tax_total, 0.0001);
    }

    public function checkCartWithMultipleZipRangeBasedTaxes(FunctionalTester $I): void
    {
        $tax11 = $I->have(TaxRate::class, [
            'country'  => $this->country,
            'is_zip'   => 1,
            'zip_code' => null,
            'zip_from' => '00000',
            'zip_to'   => '49999',
            'tax_rate' => $I->fake()->randomFloat(2, 3, 8),
        ]);
        $tax12 = $I->have(TaxRate::class, [
            'country'  => $this->country,
            'is_zip'   => 1,
            'zip_code' => null,
            'zip_from' => '50000',
            'zip_to'   => '89999',
            'tax_rate' => $I->fake()->randomFloat(2, 3, 8),
        ]);

        $taxCategory1 = $I->have(TaxCategory::class);

        $I->have(TaxMap::class, [
            'tax_rate_id'     => $tax11->id,
            'tax_category_id' => $taxCategory1->id,
        ]);
        $I->have(TaxMap::class, [
            'tax_rate_id'     => $tax12->id,
            'tax_category_id' => $taxCategory1->id,
        ]);

        $tax21 = $I->have(TaxRate::class, [
            'country'  => $this->country,
            'is_zip'   => 1,
            'zip_code' => null,
            'zip_from' => '00000',
            'zip_to'   => '49999',
            'tax_rate' => $I->fake()->randomFloat(2, 14, 25),
        ]);
        $tax22 = $I->have(TaxRate::class, [
            'country'  => $this->country,
            'is_zip'   => 1,
            'zip_code' => null,
            'zip_from' => '50000',
            'zip_to'   => '89999',
            'tax_rate' => $I->fake()->randomFloat(2, 14, 25),
        ]);

        $taxCategory2 = $I->have(TaxCategory::class);

        $I->have(TaxMap::class, [
            'tax_rate_id'     => $tax21->id,
            'tax_category_id' => $taxCategory2->id,
        ]);
        $I->have(TaxMap::class, [
            'tax_rate_id'     => $tax22->id,
            'tax_category_id' => $taxCategory2->id,
        ]);

        $config1 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategory1->id,
            ],
        ];
        $product1 = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, $config1);

        $config2 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategory2->id,
            ],
        ];
        $product2 = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, $config2);

        $customer = $I->have(Customer::class);

        $addressZip012345 = $I->have(CustomerAddress::class, [
            'customer_id'     => $customer->id,
            'postcode'        => '012345',
            'vat_id'          => 'DE123456789',
            'country'         => $this->country,
            'default_address' => 1,
        ]);

        Cart::addProduct($product1->id, [
            '_token'     => session('_token'),
            'product_id' => $product1->id,
            'quantity'   => 1,
        ]);

        $expectedTaxAmount11 = round(
            round(1 * $product1->price, self::CART_TOTAL_PRECISION)
                * $tax11->tax_rate / 100,
            self::TAX_AMOUNT_PRECISION
        );
        $expectedTaxAmount12 = round(
            round(1 * $product1->price, self::CART_TOTAL_PRECISION)
                * $tax12->tax_rate / 100,
            self::TAX_AMOUNT_PRECISION
        );

        $expectedTaxAmount21 = round(
            round(1 * $product2->price, self::CART_TOTAL_PRECISION)
                * $tax21->tax_rate / 100,
            self::TAX_AMOUNT_PRECISION
        );
        $expectedTaxAmount22 = round(
            round(1 * $product2->price, self::CART_TOTAL_PRECISION)
                * $tax22->tax_rate / 100,
            self::TAX_AMOUNT_PRECISION
        );

        Cart::saveCustomerAddress(
            [
                'billing'  => [
                    'address1'         => $addressZip012345->address1,
                    'use_for_shipping' => 1,
                    'email'            => $customer->email,
                    'company_name'     => $addressZip012345->company_name,
                    'first_name'       => $addressZip012345->first_name,
                    'last_name'        => $addressZip012345->last_name,
                    'city'             => $addressZip012345->city,
                    'state'            => $addressZip012345->state,
                    'postcode'         => $addressZip012345->postcode,
                    'country'          => $addressZip012345->country,
                ],
                'shipping' => [
                    'address1' => '',
                ],
            ]
        );

        $I->wantToTest('customer address with postcode in range of 00000 - 49999');
        $I->amOnPage('/checkout/cart');

        $I->seeInSource('Tax ' . $tax11->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax11->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount11), '#basetaxamount-' . core()->taxRateAsIdentifier($tax11->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax12->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax12->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount12), '#basetaxamount-' . core()->taxRateAsIdentifier($tax12->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax21->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax21->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount21), '#basetaxamount-' . core()->taxRateAsIdentifier($tax21->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax22->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax22->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount22), '#basetaxamount-' . core()->taxRateAsIdentifier($tax22->tax_rate));

        Cart::addProduct($product2->id, [
            '_token'     => session('_token'),
            'product_id' => $product2->id,
            'quantity'   => 1,
        ]);

        $I->amOnPage('/checkout/cart');

        $I->seeInSource('Tax ' . $tax11->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax11->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount11), '#basetaxamount-' . core()->taxRateAsIdentifier($tax11->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax12->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax12->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount12), '#basetaxamount-' . core()->taxRateAsIdentifier($tax12->tax_rate));

        $I->seeInSource('Tax ' . $tax21->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax21->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount21), '#basetaxamount-' . core()->taxRateAsIdentifier($tax21->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax22->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax22->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount22), '#basetaxamount-' . core()->taxRateAsIdentifier($tax22->tax_rate));

        $I->seeInSource(core()->currency($expectedTaxAmount11), '#basetaxamount-' . core()->taxRateAsIdentifier($tax11->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount21), '#basetaxamount-' . core()->taxRateAsIdentifier($tax21->tax_rate));


        $I->wantToTest('customer address with postcode in range of 50000 - 89999');
        $addressZip67890 = $I->have(CustomerAddress::class, [
            'customer_id'     => $customer->id,
            'postcode'        => '67890',
            'vat_id'          => 'DE123456789',
            'country'         => $this->country,
            'default_address' => 1,
        ]);

        Cart::saveCustomerAddress(
            [
                'billing'  => [
                    'address1'         => $addressZip67890->address1,
                    'use_for_shipping' => 1,
                    'email'            => $customer->email,
                    'company_name'     => $addressZip67890->company_name,
                    'first_name'       => $addressZip67890->first_name,
                    'last_name'        => $addressZip67890->last_name,
                    'city'             => $addressZip67890->city,
                    'state'            => $addressZip67890->state,
                    'postcode'         => $addressZip67890->postcode,
                    'country'          => $addressZip67890->country,
                ],
                'shipping' => [
                    'address1' => '',
                ],
            ]
        );

        $I->amOnPage('/checkout/cart');

        $I->dontSeeInSource('Tax ' . $tax11->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax11->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount11), '#basetaxamount-' . core()->taxRateAsIdentifier($tax11->tax_rate));

        $I->seeInSource('Tax ' . $tax12->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax12->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount12), '#basetaxamount-' . core()->taxRateAsIdentifier($tax12->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax21->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax21->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount21), '#basetaxamount-' . core()->taxRateAsIdentifier($tax21->tax_rate));

        $I->seeInSource('Tax ' . $tax22->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax22->tax_rate));
        $I->seeInSource(core()->currency(round($product2->price * $tax22->tax_rate / 100, 2)), '#basetaxamount-' . core()->taxRateAsIdentifier($tax22->tax_rate));

        $I->seeInSource(core()->currency($expectedTaxAmount12), '#basetaxamount-' . core()->taxRateAsIdentifier($tax12->tax_rate));
        $I->seeInSource(core()->currency($expectedTaxAmount22), '#basetaxamount-' . core()->taxRateAsIdentifier($tax22->tax_rate));

        $I->wantToTest('customer address with postcode in range of 90000 - 99000');
        $I->wanttoTest('as we dont have any taxes in this zip range');
        $addressZip98765 = $I->have(CustomerAddress::class, [
            'customer_id'     => $customer->id,
            'postcode'        => '98765',
            'vat_id'          => 'DE123456789',
            'country'         => $this->country,
            'default_address' => 1,
        ]);

        Cart::saveCustomerAddress(
            [
                'billing'  => [
                    'address1'         => $addressZip98765->address1,
                    'use_for_shipping' => 1,
                    'email'            => $customer->email,
                    'company_name'     => $addressZip98765->company_name,
                    'first_name'       => $addressZip98765->first_name,
                    'last_name'        => $addressZip98765->last_name,
                    'city'             => $addressZip98765->city,
                    'state'            => $addressZip98765->state,
                    'postcode'         => $addressZip98765->postcode,
                    'country'          => $addressZip98765->country,
                ],
                'shipping' => [
                    'address1' => '',
                ],
            ]
        );

        $I->amOnPage('/checkout/cart');

        $I->dontSeeInSource('Tax ' . $tax11->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax11->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount11), '#basetaxamount-' . core()->taxRateAsIdentifier($tax11->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax12->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax12->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount12), '#basetaxamount-' . core()->taxRateAsIdentifier($tax12->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax21->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax21->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount21), '#basetaxamount-' . core()->taxRateAsIdentifier($tax21->tax_rate));

        $I->dontSeeInSource('Tax ' . $tax22->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($tax22->tax_rate));
        $I->dontSeeInSource(core()->currency($expectedTaxAmount22), '#basetaxamount-' . core()->taxRateAsIdentifier($tax22->tax_rate));
    }
}
