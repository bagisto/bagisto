<?php

namespace Tests\Functional\Admin\Sales;

use FunctionalTester;
use Webkul\Sales\Models\Invoice;
use Webkul\Sales\Models\OrderAddress;

class InvoiceCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.sales'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.invoices'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.sales.invoices.index');
    }
}
