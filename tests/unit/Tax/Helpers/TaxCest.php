<?php

namespace Tests\Unit\Tax\Helpers;

use Illuminate\Support\Facades\Config;
use UnitTester;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;
use Cart;

class TaxCest
{
    public $scenario;

    private const PRODUCT1_QTY = 11;
    private const PRODUCT2_QTY = 7;

    private const CART_TOTAL_PRECISION = 2;

    public function _before(UnitTester $I)
    {
        $country = strtoupper(Config::get('app.default_country')) ?? 'DE';

        $tax1 = $I->have(TaxRate::class, [
            'country' => $country,
        ]);
        $taxCategorie1 = $I->have(TaxCategory::class);
        $I->have(TaxMap::class, [
            'tax_rate_id'     => $tax1->id,
            'tax_category_id' => $taxCategorie1->id,
        ]);

        $tax2 = $I->have(TaxRate::class, [
            'country' => $country,
        ]);
        $taxCategorie2 = $I->have(TaxCategory::class);
        $I->have(TaxMap::class, [
            'tax_rate_id'     => $tax2->id,
            'tax_category_id' => $taxCategorie2->id,
        ]);

        $config1 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategorie1->id,
            ],
        ];
        $product1 = $I->haveProduct(\Webkul\Core\Helpers\Laravel5Helper::SIMPLE_PRODUCT, $config1);

        $config2 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategorie2->id,
            ],
        ];
        $product2 = $I->haveProduct(\Webkul\Core\Helpers\Laravel5Helper::SIMPLE_PRODUCT, $config2);

        Cart::addProduct($product1->id, [
            '_token'     => session('_token'),
            'product_id' => $product1->id,
            'quantity'   => self::PRODUCT1_QTY,
        ]);

        Cart::addProduct($product2->id, [
            '_token'     => session('_token'),
            'product_id' => $product2->id,
            'quantity'   => self::PRODUCT2_QTY,
        ]);

        // rounded by precision of 2 because this are sums of corresponding tax categories
        $expectedTaxAmount1 = round(
            round(self::PRODUCT1_QTY * $product1->price, self::CART_TOTAL_PRECISION)
            * $tax1->tax_rate / 100,
            self::CART_TOTAL_PRECISION
        );

        $expectedTaxAmount2 = round(
            round(self::PRODUCT2_QTY * $product2->price, self::CART_TOTAL_PRECISION)
            * $tax2->tax_rate / 100,
            self::CART_TOTAL_PRECISION
        );

        $this->scenario = [
            'cart'             => Cart::getCart(),
            'expectedTaxRates' => [
                (string)round((float)$tax1->tax_rate, 4) => $expectedTaxAmount1,
                (string)round((float)$tax2->tax_rate, 4) => $expectedTaxAmount2,
            ],
            'expectedTaxTotal' =>
                round($expectedTaxAmount1 + $expectedTaxAmount2, self::CART_TOTAL_PRECISION),
        ];
    }

    public function testGetTaxRatesWithAmount(UnitTester $I)
    {
        $result = $I->executeFunction(
            \Webkul\Tax\Helpers\Tax::class,
            'getTaxRatesWithAmount',
            [$this->scenario['cart'], false]
        );

        foreach ($this->scenario['expectedTaxRates'] as $taxRate => $taxAmount) {
            $I->assertTrue(array_key_exists($taxRate, $result));
            $I->assertEquals($taxAmount, $result[$taxRate]);
        }
    }

    public function testGetTaxTotal(UnitTester $I)
    {
        $result = $I->executeFunction(
            \Webkul\Tax\Helpers\Tax::class,
            'getTaxTotal',
            [$this->scenario['cart'], false]
        );

        $I->assertEquals($this->scenario['expectedTaxTotal'], $result);
    }
}
