<?php

namespace Tests\Unit\Checkout\Cart\Models;

use UnitTester;
use Webkul\Checkout\Models\Cart as CartModel;
use Webkul\Customer\Repositories\WishlistRepository;
use Webkul\Product\Repositories\ProductRepository;
use Webkul\Shop\Http\Controllers\CartController;
use Cart;

class CartModelCest
{
    public $productWithQuantityBox;
    public $productWithoutQuantityBox;

    public function _before(UnitTester $I)
    {
        $this->productWithQuantityBox = $I->haveProduct();
        $this->productWithoutQuantityBox = $I->haveProduct();
    }

    public function testHasProductsWithQuantityBox(UnitTester $I)
    {
        $this->createMixedCart();
        $I->assertTrue($I->executeFunction(CartModel::class, 'hasProductsWithQuantityBox'));

        $this->createCardWithProductsWithQuantityBox();
        $I->assertTrue($I->executeFunction(CartModel::class, 'hasProductsWithQuantityBox'));

        $this->createCardWithProductsWithQuantityBox();
        $I->assertFalse($I->executeFunction(CartModel::class, 'hasProductsWithQuantityBox'));
    }

    private function createMixedCart()
    {
        $this->clearCart();

        Cart::addProduct($this->productWithQuantityBox->id);
        Cart::addProduct($this->productWithoutQuantityBox->id);
    }

    private function createCardWithProductsWithQuantityBox()
    {
        $this->clearCart();
        Cart::addProduct($this->productWithQuantityBox->id);
    }

    private  function createCardWithoutProductsWithQuantityBox()
    {
        $this->clearCart();
        Cart::addProduct($this->productWithoutQuantityBox);
    }

    private function clearCart()
    {
        Cart::clear();
    }
}