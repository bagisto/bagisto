<?php

namespace Tests\Unit\Checkout\Cart\Controllers;

use UnitTester;
use Webkul\Checkout\Models\Cart;
use Webkul\Shop\Http\Controllers\CartController;

class CartControllerCest
{
    public function _before(UnitTester $I)
    {
    }

    public function testOnWarningAddingToCart(UnitTester $I)
    {
        $scenarios = [
            [
                'result' => ['key' => 'value', 'warning' => 'Hello World. Something went wrong.'],
                'expected' => true,
            ],
            [
                'result' => ['key' => 'value'],
                'expected' => false,
            ],
            [
                'result' => new Cart(),
                'expected' => false,
            ],
        ];

        foreach ($scenarios as $scenario) {
            $I->assertEquals($scenario['expected'], $I->executeFunction(CartController::class, 'onWarningAddingToCart', [$scenario['result']]));
        }
    }
}