<?php

namespace Tests\Functional\Cart;

use FunctionalTester;
use Illuminate\Support\Facades\Config;
use Webkul\Tax\Models\TaxMap;
use Webkul\Tax\Models\TaxRate;
use Webkul\Tax\Models\TaxCategory;
use Cart;

class CartCest
{
    private $country;
    private $product1, $product2;
    private $tax1, $tax2;

    function _before(FunctionalTester $I)
    {
        $this->country = strtoupper(Config::get('app.default_country')) ?? 'DE';

        $this->tax1 = $I->have(TaxRate::class, [
            'country' => $this->country
        ]);
        $taxCategorie1 = $I->have(TaxCategory::class);
        $I->have(TaxMap::class, [
            'tax_rate_id' => $this->tax1->id,
            'tax_category_id' => $taxCategorie1->id
        ]);

        $this->tax2 = $I->have(TaxRate::class, [
            'country' => $this->country
        ]);
        $taxCategorie2 = $I->have(TaxCategory::class);
        $I->have(TaxMap::class, [
            'tax_rate_id' => $this->tax2->id,
            'tax_category_id' => $taxCategorie2->id
        ]);

        $config1 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategorie1->id,
            ],
        ];
        $this->product1 = $I->haveProduct(\Webkul\Core\Helpers\Laravel5Helper::SIMPLE_PRODUCT, $config1);

        $config2 = [
            'productInventory' => ['qty' => 100],
            'attributeValues'  => [
                'status'          => true,
                'new'             => 1,
                'tax_category_id' => $taxCategorie2->id,
            ],
        ];
        $this->product2 = $I->haveProduct(\Webkul\Core\Helpers\Laravel5Helper::SIMPLE_PRODUCT, $config2);
    }

    public function checkCartWithMultipleTaxRates(FunctionalTester $I)
    {
        $prod1Quantity = $I->fake()->numberBetween(9, 30);
        // quantity of product1 should be not even
        if ($prod1Quantity % 2 !== 0) {
            $prod1Quantity -= 1;
        }

        $prod2Quantity = $I->fake()->numberBetween(9, 30);
        // quantity of product2 should be even
        if ($prod2Quantity % 2 == 0) {
            $prod2Quantity -= 1;
        }

        Cart::addProduct($this->product1->id, [
            '_token'     => session('_token'),
            'product_id' => $this->product1->id,
            'quantity'   => 1,
        ]);

        $I->amOnPage('/checkout/cart');
        $I->see('Tax ' . $this->tax1->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($this->tax1->tax_rate));
        $I->see(
            core()->currency(round($this->product1->price * $this->tax1->tax_rate / 100, 2)),
            '#basetaxamount-' . core()->taxRateAsIdentifier($this->tax1->tax_rate)
        );

        Cart::addProduct($this->product1->id, [
            '_token'     => session('_token'),
            'product_id' => $this->product1->id,
            'quantity'   => $prod1Quantity,
        ]);

        $I->amOnPage('/checkout/cart');
        $I->see('Tax ' . $this->tax1->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($this->tax1->tax_rate));
        $I->see(
            core()->currency(round(($prod1Quantity + 1) * $this->product1->price * $this->tax1->tax_rate / 100, 2)),
            '#basetaxamount-' . core()->taxRateAsIdentifier($this->tax1->tax_rate)
        );

        Cart::addProduct($this->product2->id, [
            '_token'     => session('_token'),
            'product_id' => $this->product2->id,
            'quantity'   => $prod2Quantity,
        ]);

        $I->amOnPage('/checkout/cart');
        $I->see('Tax ' . $this->tax1->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($this->tax1->tax_rate));
        $taxAmount1 = round(($prod1Quantity + 1) * $this->product1->price * $this->tax1->tax_rate / 100, 2);
        $I->see(core()->currency($taxAmount1),'#basetaxamount-' . core()->taxRateAsIdentifier($this->tax1->tax_rate));

        $I->see('Tax ' . $this->tax2->tax_rate . ' %', '#taxrate-' . core()->taxRateAsIdentifier($this->tax2->tax_rate));
        $taxAmount2 = round($prod2Quantity * $this->product2->price * $this->tax2->tax_rate / 100, 2);
        $I->see(core()->currency($taxAmount2),'#basetaxamount-' . core()->taxRateAsIdentifier($this->tax2->tax_rate));

        $cart = Cart::getCart();

        $I->assertEquals(2, $cart->items_count);
        $I->assertEquals((float)($prod1Quantity + 1 + $prod2Quantity), $cart->items_qty);
        $I->assertEquals($taxAmount1 + $taxAmount2, $cart->tax_total);
    }
}