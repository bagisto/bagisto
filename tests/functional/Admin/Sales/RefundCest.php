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
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.sales'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.refunds'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.sales.refunds.index');
        $I->see($refund->id, '//script[@type="text/x-template"]');
    }
}
