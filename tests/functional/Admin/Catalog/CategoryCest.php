<?php

namespace Tests\Functional\Admin\Catalog;

use FunctionalTester;

class CategoryCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.catalog.categories.index');
        $I->seeCurrentRouteIs('admin.catalog.categories.index');

        $I->sendAjaxGetRequest(route('admin.catalog.categories.index'));
        $I->seeResponseCodeIsSuccessful();
    }
}
