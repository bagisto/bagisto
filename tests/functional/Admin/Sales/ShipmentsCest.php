<?php

namespace Tests\Functional\Admin\Sales;

use FunctionalTester;
use Webkul\Sales\Models\Shipment;

class ShipmentsCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $shipment = $I->have(Shipment::class);

        $I->loginAsAdmin();
        
        $I->amOnAdminRoute('admin.sales.shipments.index');
        $I->seeCurrentRouteIs('admin.sales.shipments.index');
      
        $I->see("{$shipment->id}", '//script[@type="text/x-template"]');
        $I->see("{$shipment->total_qty}", '//script[@type="text/x-template"]');
    }
}
