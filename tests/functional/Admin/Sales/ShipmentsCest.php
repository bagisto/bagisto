<?php

namespace Tests\Functional\Admin\Sales;

use FunctionalTester;

class ShipmentsCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.sales.shipments.index');
        $I->seeCurrentRouteIs('admin.sales.shipments.index');

        $I->sendAjaxGetRequest(route('admin.sales.shipments.index'));
        $I->seeResponseCodeIsSuccessful();
    }
}
