<?php
namespace Tests\Webkul\Unit\Shop;

use FunctionalTester;
use Faker\Factory;
use Cart;

class GuestCheckoutCest
{
    private $faker,
        $productNoGuestCheckout, $productGuestCheckout;

    function _before(FunctionalTester $I) {

        $this->faker = Factory::create();

        $pConfigDefault = [
            'productInventory'  => ['qty' => $this->faker->randomNumber(2)],
            'attributeValues'   => [
                'status' => true,
                'new' => 1,
                'guest_checkout' => 0
            ],
        ];
        $pConfigGuestCheckout = [
            'productInventory'  => ['qty' => $this->faker->randomNumber(2)],
            'attributeValues'   => [
                'status' => true,
                'new' => 1,
                'guest_checkout' => 1
            ],
        ];

        $this->productNoGuestCheckout = $I->haveProduct($pConfigDefault, ['simple']);
        $this->productNoGuestCheckout->refresh();

        $this->productGuestCheckout = $I->haveProduct($pConfigGuestCheckout, ['simple']);
        $this->productGuestCheckout->refresh();
    }

    public function testGuestCheckout(FunctionalTester $I) {
        $I->amGoingTo('try to add products to cart with guest checkout turned on or off');

        $scenarios = [
            [
                'name' => 'false / false',
                'globalConfig' => 0,
                'product' => $this->productNoGuestCheckout,
                'expectedRoute' => 'customer.session.index'
            ],
            [
                'name' => 'false / true',
                'globalConfig' => 0,
                'product' => $this->productGuestCheckout,
                'expectedRoute' => 'customer.session.index'
            ],
            [
                'name' => 'true / false',
                'globalConfig' => 1,
                'product' => $this->productNoGuestCheckout,
                'expectedRoute' => 'customer.session.index'
            ],
            [
                'name' => 'true / true',
                'globalConfig' => 1,
                'product' => $this->productGuestCheckout,
                'expectedRoute' => 'shop.checkout.onepage.index'
            ],
        ];

        foreach ($scenarios as $scenario) {
            $I->wantTo('test conjunction "' . $scenario['name'] . '" with globalConfig = ' . $scenario['globalConfig'] . ' && product config = ' . $scenario['product']->getAttribute('guest_checkout'));
            $I->setConfigData(['catalog.products.guest-checkout.allow-guest-checkout' => $scenario['globalConfig']]);
            $I->assertEquals($scenario['globalConfig'], core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout'));
            $I->amOnRoute('shop.home.index');
            $I->see($scenario['product']->name, '//div[@class="product-information"]/div[@class="product-name"]');
            $I->click(__('shop::app.products.add-to-cart'),
                '//form[input[@name="product_id"][@value="' . $scenario['product']->id . '"]]/button');
            $I->seeInSource(__('shop::app.checkout.cart.item.success'));
            $I->amOnRoute('shop.checkout.cart.index');
            $I->see('Shopping Cart', '//div[@class="title"]');
            $I->makeHtmlSnapshot('guestCheckout_'.$scenario['globalConfig'].'_'.$scenario['product']->getAttribute('guest_checkout'));
            $I->see($scenario['product']->name, '//div[@class="item-title"]');
            $I->click( __('shop::app.checkout.cart.proceed-to-checkout'), '//a[@href="' . route('shop.checkout.onepage.index') . '"]');
            $I->seeCurrentRouteIs($scenario['expectedRoute']);
            $cart = Cart::getCart();
            $I->assertTrue(Cart::removeItem($cart->items[0]->id));
        }
    }
}