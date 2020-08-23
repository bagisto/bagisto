<?php

namespace Tests\Unit\Checkout\Cart\Controllers;

use UnitTester;
use Codeception\Example;
use Webkul\Checkout\Models\Cart;
use Webkul\Shop\Http\Controllers\CartController;

class CartControllerCest
{
    /**
     * @param \UnitTester $I
     *
     * @param \Example    $scenario
     *
     * @throws \Exception
     * @dataProvider getOnFailureAddingToCartScenarios
     */
    public function testOnFailureAddingToCart(UnitTester $I, Example $scenario): void
    {
        $I->assertEquals($scenario['expected'],
            $I->executeFunction(
                CartController::class,
                'onFailureAddingToCart',
                [$scenario['result']]
            )
        );
    }

    protected function getOnFailureAddingToCartScenarios(): array
    {
        return [
            [
                'result'   => ['key' => 'value', 'warning' => 'Hello World. Something went wrong.'],
                'expected' => true,
            ],
            [
                'result'   => ['key' => 'value', 'info' => 'This is only a test.'],
                'expected' => true,
            ],
            [
                'result'   => ['key' => 'value'],
                'expected' => false,
            ],
            [
                'result'   => new Cart(),
                'expected' => false,
            ],
        ];
    }
}