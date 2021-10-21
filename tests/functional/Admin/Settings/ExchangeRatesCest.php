<?php

namespace Tests\Functional\Admin\Settings;

use FunctionalTester;

class ExchangeRatesCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');

        $I->click(__('admin::app.layouts.settings'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.exchange-rates'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.exchange_rates.index');
    }
}
