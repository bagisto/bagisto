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

        $I->amOnAdminRoute('admin.groups.index');
        $I->seeCurrentRouteIs('admin.groups.index');

        $I->see("{$group->id}", '//script[@type="text/x-template"]');
    }
}
