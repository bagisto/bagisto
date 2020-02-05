<?php

namespace Tests\Functional\Admin\Customer;


use FunctionalTester;
use Webkul\Customer\Models\Customer;


class CustomerCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $customer = $I->have(Customer::class);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.customers'), '//*[contains(@class, "navbar-left")]');
        $I->seeCurrentRouteIs('admin.customer.index');
        $I->click(__('admin::app.layouts.customers'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.customer.index');
        $I->see($customer->id, '//script[@type="text/x-template"]');
        $I->see($customer->last_name, '//script[@type="text/x-template"]');
    }
}
