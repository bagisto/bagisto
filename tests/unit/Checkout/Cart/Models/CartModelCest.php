<?php

namespace Tests\Unit\Checkout\Cart\Models;

use UnitTester;
use Faker\Factory;
use Webkul\Core\Helpers\Laravel5Helper;
use Cart;

class CartModelCest
{
    public $cart;
    public $faker;
    public $sessionToken;
    public $productWithQuantityBox;
    public $productWithoutQuantityBox;

    public function _before(UnitTester $I)
    {
        $this->faker = Factory::create();

        $this->sessionToken = $this->faker->uuid;
        session(['_token' => $this->sessionToken]);

        $productConfig = [
            'productAttributes' => [],
            'productInventory'  => [
                'qty' => 10,
            ],
            'attributeValues'   => [
                'status' => 1,
            ],
        ];
        $this->productWithQuantityBox = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, $productConfig);

        $this->productWithoutQuantityBox = $I->haveProduct(Laravel5Helper::DOWNLOADABLE_PRODUCT, $productConfig);
    }

    public function testHasProductsWithQuantityBox(UnitTester $I)
    {
        $I->wantTo('check function with cart, that contains a product with QuantityBox() == false');
        $this->cart = Cart::addProduct($this->productWithoutQuantityBox->id, [
            '_token' => session('_token'),
            'product_id' => $this->productWithoutQuantityBox->id,
            'links' => $this->productWithoutQuantityBox->downloadable_links->pluck('id')->all(),
            'quantity' => 1,
        ]);
        $cartItemIdOfProductWithoutQuantityBox = $this->cart->items[0]->id;
        $I->assertFalse(Cart::getCart()->hasProductsWithQuantityBox());

        $I->wantTo('check function with cart, that is mixed');
        Cart::addProduct($this->productWithQuantityBox->id, [
            '_token' => session('_token'),
            'product_id' => $this->productWithQuantityBox->id,
            'quantity' => 1,
        ]);
        $I->assertTrue(Cart::getCart()->hasProductsWithQuantityBox());

        $I->wantTo('check function with cart, that contains a product with QuantityBox() == true');
        Cart::removeItem($cartItemIdOfProductWithoutQuantityBox);
        $I->assertTrue(Cart::getCart()->hasProductsWithQuantityBox());
    }
}