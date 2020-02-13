<?php

namespace Tests\Webkul\Unit\Shop;

use Codeception\Example;
use FunctionalTester;
use Faker\Factory;
use Cart;
use Webkul\Core\Helpers\Laravel5Helper;

class GuestCheckoutCest
{
    private $faker,
        $productNoGuestCheckout, $productGuestCheckout;

    function _before(FunctionalTester $I)
    {

        $this->faker = Factory::create();

        $pConfigDefault = [
            'productInventory' => ['qty' => $this->faker->numberBetween(1, 1000)],
            'attributeValues'  => [
                'status'         => true,
                'new'            => 1,
                'guest_checkout' => 0
            ],
        ];
        $pConfigGuestCheckout = [
            'productInventory' => ['qty' => $this->faker->numberBetween(1, 1000)],
            'attributeValues'  => [
                'status'         => true,
                'new'            => 1,
                'guest_checkout' => 1
            ],
        ];

        $this->productNoGuestCheckout = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, $pConfigDefault, ['simple']);
        $this->productNoGuestCheckout->refresh();

        $this->productGuestCheckout = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, $pConfigGuestCheckout, ['simple']);
        $this->productGuestCheckout->refresh();
    }

    /**
     * @param FunctionalTester $I
     * @param Example          $example
     *
     * @dataProvider guestCheckoutProvider
     */
    public function testGuestCheckout(FunctionalTester $I, Example $example): void
    {
        $product = ($example['guest_product']) ? $this->productGuestCheckout : $this->productNoGuestCheckout;

        $I->amGoingTo('try to add products to cart with guest checkout turned on or off');

        $I->wantTo('test conjunction "' . $example['name'] . '" with globalConfig = ' . $example['globalConfig'] . ' && product config = ' . $product->getAttribute('guest_checkout'));
        $I->setConfigData(['catalog.products.guest-checkout.allow-guest-checkout' => $example['globalConfig']]);
        $I->assertEquals($example['globalConfig'],
            core()->getConfigData('catalog.products.guest-checkout.allow-guest-checkout'));
        $I->amOnRoute('shop.home.index');
        $I->see($product->name, '//div[@class="product-information"]/div[@class="product-name"]');
        $I->click(__('shop::app.products.add-to-cart'),
            '//form[input[@name="product_id"][@value="' . $product->id . '"]]/button');

        $I->amOnRoute('shop.checkout.cart.index');
        $I->see('Shopping Cart', '//div[@class="title"]');
        $I->makeHtmlSnapshot('guestCheckout_' . $example['globalConfig'] . '_' . $product->getAttribute('guest_checkout'));
        $I->see($product->name, '//div[@class="item-title"]');
        $I->click(__('shop::app.checkout.cart.proceed-to-checkout'),
            '//a[@href="' . route('shop.checkout.onepage.index') . '"]');
        $I->seeCurrentRouteIs($example['expectedRoute']);
        $cart = Cart::getCart();
        $I->assertTrue(Cart::removeItem($cart->items[0]->id));
    }

    protected function guestCheckoutProvider(): array
    {
        return [
            [
                'name'          => 'false / false',
                'globalConfig'  => 0,
                'guest_product' => false,
                'product'       => $this->productNoGuestCheckout,
                'expectedRoute' => 'customer.session.index'
            ],
            [
                'name'          => 'false / true',
                'globalConfig'  => 0,
                'guest_product' => true,
                'product'       => $this->productGuestCheckout,
                'expectedRoute' => 'customer.session.index'
            ],
            [
                'name'          => 'true / false',
                'globalConfig'  => 1,
                'guest_product' => false,
                'product'       => $this->productNoGuestCheckout,
                'expectedRoute' => 'customer.session.index'
            ],
            [
                'name'          => 'true / true',
                'globalConfig'  => 1,
                'guest_product' => true,
                'product'       => $this->productGuestCheckout,
                'expectedRoute' => 'shop.checkout.onepage.index'
            ],
        ];
    }
}