<?php

namespace Tests\Unit\Tax\Helpers;

use Faker\Factory;
use Illuminate\Support\Facades\Config;
use UnitTester;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;
use Cart;

class TaxCest
{
    public $scenario;

    public function _before(UnitTester $I)
    {
        $country = Config::get('app.default_country');

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
        $product1 = $I->haveProduct($config1, ['simple']);

        $config2 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategorie2->id,
            ],
        ];
        $product2 = $I->haveProduct($config2, ['simple']);

        Cart::addProduct($product1->id, [
            '_token'     => session('_token'),
            'product_id' => $product1->id,
            'quantity'   => 11,
        ]);

        Cart::addProduct($product2->id, [
            '_token'     => session('_token'),
            'product_id' => $product2->id,
            'quantity'   => 7,
        ]);


        $this->scenario = [
            'object'           => Cart::getCart(),
            'expectedTaxRates' => [
                (string)round((float)$tax1->tax_rate, \Webkul\Tax\Helpers\Tax::TAX_PRECISION)
                => round(11 * $product1->price * $tax1->tax_rate / 100, 4),

                (string)round((float)$tax2->tax_rate, \Webkul\Tax\Helpers\Tax::TAX_PRECISION)
                => round(7 * $product2->price * $tax2->tax_rate / 100, 4),
            ],
            'expectedTaxTotal' =>
                round(
                    round(11 * $product1->price * $tax1->tax_rate / 100, 2)
                    + round(7 * $product2->price * $tax2->tax_rate / 100, 2)
                    , 2),
        ];
    }

    public function testGetTaxRatesWithAmount(UnitTester $I)
    {
        $result = $I->executeFunction(
            \Webkul\Tax\Helpers\Tax::class,
            'getTaxRatesWithAmount',
            [$this->scenario['object'], false]
        );

        foreach ($result as $taxRate => $taxAmount) {
            $I->assertTrue(array_key_exists($taxRate, $result));
            $I->assertEquals($this->scenario['expectedTaxRates'][$taxRate], $taxAmount);
        }
    }

    public function testGetTaxTotal(UnitTester $I)
    {
        $result = $I->executeFunction(
            \Webkul\Tax\Helpers\Tax::class,
            'getTaxTotal',
            [$this->scenario['object'], false]
        );

        $I->assertEquals($this->scenario['expectedTaxTotal'], $result);
    }
}
