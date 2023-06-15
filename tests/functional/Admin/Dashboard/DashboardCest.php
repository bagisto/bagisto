<?php

namespace Tests\Functional\Admin\Dashboard;

use FunctionalTester;

class DashboardCest
{
    public function _before(FunctionalTester $I)
    {
        $I->loginAsAdmin();
    }

    public function testIndexPage(FunctionalTester $I)
    {
        $I->wantTo('ensure that dashboard page works');
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->seeCurrentRouteIs('admin.dashboard.index');

        $I->sendAjaxGetRequest(route('admin.dashboard.index'));
        $I->seeResponseCodeIsSuccessful();
    }
}
