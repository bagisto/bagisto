<?php

namespace Tests\Functional\Admin\Customer;


use FunctionalTester;
use Webkul\Customer\Models\CustomerGroup;


class GroupsCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $group = $I->have(CustomerGroup::class);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.customers'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.groups'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.groups.index');
        $I->see($group->id, '//script[@type="text/x-template"]');
        $I->see($group->name, '//script[@type="text/x-template"]');
    }
}
