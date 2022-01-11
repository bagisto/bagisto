<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;
use Webkul\Customer\Models\Wishlist;

class CartCest extends CustomerCest
{
    public function testForFetchingTheCart(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $product = $I->haveSimpleProduct();

        $cart = $I->haveCart([
            'customer_id' => $customer->id,
        ]);

        $cartItem = $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
        ]);

        $I->haveAllNecessaryHeaders();

        $I->sendGet($this->getVersionRoute('customer/cart'));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'id'                  => $cart->id,
                'customer_email'      => $customer->email,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
                'items'               => [
                    [
                        'id'      => $cartItem->id,
                        'product' => [
                            'id' => $product->id,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testForAddingItemToTheCart(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $I->haveAllNecessaryHeaders();

        $product = $I->haveSimpleProduct();

        $I->sendPost($this->getVersionRoute('customer/cart/add/' . $product->id), [
            'quantity' => 1,
        ]);

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'customer_email'      => $customer->email,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
                'items'               => [
                    [
                        'product' => [
                            'id' => $product->id,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testForUpdatingTheCart(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $product = $I->haveSimpleProduct();

        $cart = $I->haveCart([
            'customer_id' => $customer->id,
        ]);

        $cartItem = $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
        ]);

        $I->haveAllNecessaryHeaders();

        $I->sendPut($this->getVersionRoute('customer/cart/update'), [
            'qty' => [
                $cartItem->id => $expectedQuantity = 2,
            ],
        ]);

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'id'                  => $cart->id,
                'customer_email'      => $customer->email,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
                'items'               => [
                    [
                        'id'       => $cartItem->id,
                        'quantity' => $expectedQuantity,
                        'product'  => [
                            'id' => $product->id,
                        ],
                    ],
                ],
            ],
        ]);
    }

    public function testForRemovingItemFromTheCart(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $cart = $I->haveCart([
            'customer_id' => $customer->id,
        ]);

        $product1 = $I->haveSimpleProduct();

        $cartItem1 = $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product1->id,
        ]);

        $product2 = $I->haveSimpleProduct();

        $cartItem2 = $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product2->id,
        ]);

        $I->assertEquals(2, \Webkul\Checkout\Facades\Cart::getCart()->items()->count());

        $I->haveAllNecessaryHeaders();

        $I->sendDelete($this->getVersionRoute('customer/cart/remove/' . $cartItem1->id));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'id'                  => $cart->id,
                'customer_email'      => $customer->email,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
                'items'               => [
                    [
                        'id'       => $cartItem2->id,
                        'quantity' => $cartItem2->quantity,
                        'product'  => [
                            'id' => $product2->id,
                        ],
                    ],
                ],
            ],
        ]);

        $I->assertEquals(1, \Webkul\Checkout\Facades\Cart::getCart()->items()->count());
    }

    public function testForEmptyCart(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $product = $I->haveSimpleProduct();

        $cart = $I->haveCart([
            'customer_id' => $customer->id,
        ]);

        $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
        ]);

        $I->assertEquals(1, \Webkul\Checkout\Facades\Cart::getCart()->items()->count());

        $I->haveAllNecessaryHeaders();

        $I->sendDelete($this->getVersionRoute('customer/cart/empty'));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => null,
        ]);

        $I->assertNull(\Webkul\Checkout\Facades\Cart::getCart());
    }

    public function testForMovingCartItemToWishlist(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $cart = $I->haveCart([
            'customer_id' => $customer->id,
        ]);

        $product1 = $I->haveSimpleProduct();

        $cartItem1 = $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product1->id,
        ]);

        $product2 = $I->haveSimpleProduct();

        $cartItem2 = $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product2->id,
        ]);

        $I->assertEquals(2, \Webkul\Checkout\Facades\Cart::getCart()->items()->count());

        $I->haveAllNecessaryHeaders();

        $I->sendPost($this->getVersionRoute('customer/cart/move-to-wishlist/' . $cartItem1->id));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'data' => [
                'id'                  => $cart->id,
                'customer_email'      => $customer->email,
                'customer_first_name' => $customer->first_name,
                'customer_last_name'  => $customer->last_name,
                'items'               => [
                    [
                        'id'       => $cartItem2->id,
                        'quantity' => $cartItem2->quantity,
                        'product'  => [
                            'id' => $product2->id,
                        ],
                    ],
                ],
            ],
        ]);

        $I->assertEquals(1, \Webkul\Checkout\Facades\Cart::getCart()->items()->count());

        $I->assertCount(1, Wishlist::all());
    }

    public function testForApplyingInvalidCouponToTheCart(ApiTester $I)
    {
        $customer = $I->amSanctumAuthenticatedCustomer();

        $product = $I->haveSimpleProduct();

        $cart = $I->haveCart([
            'customer_id' => $customer->id,
        ]);

        $I->haveCartItems([
            'cart_id'    => $cart->id,
            'product_id' => $product->id,
        ]);

        $I->assertEquals(1, \Webkul\Checkout\Facades\Cart::getCart()->items()->count());

        $I->haveAllNecessaryHeaders();

        $I->sendPost($this->getVersionRoute('customer/cart/coupon'), [
            'code' => 'INVALID_CODE',
        ]);

        $I->seeResponseCodeIs(400);
    }
}
