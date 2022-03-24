<?php

namespace Tests\Functional\Admin\Sales;

use FunctionalTester;
use Webkul\Sales\Models\Refund;

class RefundCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $refund = $I->have(Refund::class);

        $I->loginAsAdmin();
       
        $I->amOnAdminRoute('admin.sales.refunds.index');
        $I->seeCurrentRouteIs('admin.sales.refunds.index');
       
        $I->see("{$refund->id}", '//script[@type="text/x-template"]');
    }
}
