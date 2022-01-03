<?php

namespace Tests\Functional\Admin\Settings;

use FunctionalTester;

class ExchangeRatesCest
{
    /**
     * Index page test.
     *
     * @param  FunctionalTester  $I
     * @return void
     */
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.exchange_rates.index');
        $I->seeCurrentRouteIs('admin.exchange_rates.index');
    }
}
