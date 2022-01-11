<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;

class OrderCest extends CustomerCest
{
    public function testForFetchingAllTheCustomerOrders(ApiTester $I)
    {
        $order = $this->generateCashOnDeliveryOrder($I);

        $token = $I->amCreatingTokenForSanctumAuthenticatedCustomer($order->customer);

        $I->haveAllNecessaryHeaders($token);

        $I->sendGet($this->getVersionRoute('customer/orders'));

        $I->seeAllNecessarySuccessResponse();
    }
}
