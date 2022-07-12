<?php

namespace Tests\Functional\Checkout\Cart;

use FunctionalTester;
use Helper\Bagisto;

class CartCest
{
    public $cart;

    public $productWithQuantityBox;

    public $productWithoutQuantityBox;

    public function _before(FunctionalTester $I): void
    {
        $productConfig = [
            'productAttributes' => [],

            'productInventory'  => [
                'qty' => 10,
            ],

            'attributeValues'   => [
                'status' => 1,
            ],
        ];

        $this->productWithQuantityBox = $I->haveProduct(Bagisto::SIMPLE_PRODUCT, $productConfig);
        
        $this->productWithoutQuantityBox = $I->haveProduct(Bagisto::DOWNLOADABLE_PRODUCT, $productConfig);
    }

    public function checkCartWithQuantityBox(FunctionalTester $I): void
    {
        $I->useDefaultTheme();

        cart()->deactivateCurrentCartIfBuyNowIsActive();

        cart()->addProduct($this->productWithQuantityBox->id, [
            '_token'     => session('_token'),
            'product_id' => $this->productWithQuantityBox->id,
            'quantity'   => 1,
        ]);

        $I->amOnPage('/checkout/cart');

        $I->seeElement('#update_cart_button');
    }

    public function checkCartWithoutQuantityBox(FunctionalTester $I): void
    {
        $I->useDefaultTheme();

        cart()->addProduct($this->productWithoutQuantityBox->id, [
            '_token'     => session('_token'),
            'product_id' => $this->productWithoutQuantityBox->id,
            'links'      => $this->productWithoutQuantityBox->downloadable_links->pluck('id')->all(),
            'quantity'   => 1,
        ]);

        $I->amOnPage('/checkout/cart');
        
        $I->dontSeeElement('#update_cart_button');
    }
}
