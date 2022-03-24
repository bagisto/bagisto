<?php

namespace Tests\Functional\Admin\Sales;

use FunctionalTester;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\OrderAddress;

class InvoiceCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $orderAddress = $I->have(OrderAddress::class);
        $invoice = $I->have(Invoice::class,
            [
                'order_id'         => $orderAddress->order_id,
                'order_address_id' => $orderAddress->id,
            ]);

        $I->loginAsAdmin();
        
        $I->amOnAdminRoute('admin.sales.invoices.index');
        $I->seeCurrentRouteIs('admin.sales.invoices.index');
        
        $I->see("{$invoice->id}", '//script[@type="text/x-template"]');
    }
}
