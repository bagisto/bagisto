<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;
use Webkul\Sales\Models\Shipment;

class ShipmentCest extends CustomerCest
{
    public function testForFetchingAllTheCustomerShipments(ApiTester $I)
    {
        $order = $this->generateCashOnDeliveryOrder($I);

        $shipments = $I->have(Shipment::class);

        $token = $I->amCreatingTokenForSanctumAuthenticatedCustomer($order->customer);

        $I->haveAllNecessaryHeaders($token);

        $I->sendGet($this->getVersionRoute('customer/shipments'));

        $I->seeAllNecessarySuccessResponse();
    }
}
