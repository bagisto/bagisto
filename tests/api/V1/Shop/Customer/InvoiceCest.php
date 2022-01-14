<?php

namespace Tests\API\V1\Shop\Customer;

use ApiTester;
use Webkul\Sales\Models\Invoice;

class InvoiceCest extends CustomerCest
{
    public function testForFetchingAllTheCustomerInvoices(ApiTester $I)
    {
        $order = $this->generateCashOnDeliveryOrder($I);

        $invoices = $I->have(Invoice::class, [
            'order_id' => $order->id,
        ]);

        $token = $I->amCreatingTokenForSanctumAuthenticatedCustomer($order->customer);

        $I->haveAllNecessaryHeaders($token);

        $I->sendGet($this->getVersionRoute('customer/invoices'));

        $I->seeAllNecessarySuccessResponse();
    }
}
