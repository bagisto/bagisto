<?php

namespace Tests\Functional\Checkout\Cart;

use FunctionalTester;
use Cart;
use Codeception\Example;

class OrderCest
{

    /**
     * @param \FunctionalTester $I
     *
     * @example {"isGuest": true}
     *
     * @example {"isGuest": false}
     */
    public function testCheckout(FunctionalTester $I, Example $example)
    {
        if (! $example['isGuest']) {
            $I->loginAsCustomer();
        }

        $I->prepareCart();


    }

}