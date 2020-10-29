<?php

namespace Tests\Functional\Admin\Sales;


use FunctionalTester;
use Webkul\Sales\Models\Order;


class OrderCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $order = $I->have(Order::class);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.sales'), '//*[contains(@class, "navbar-left")]');
        $I->seeCurrentRouteIs('admin.sales.orders.index');
        $I->click(__('admin::app.layouts.orders'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.sales.orders.index');
        $I->see($order->id, '//script[@type="text/x-template"]');
        $I->see($order->sub_total, '//script[@type="text/x-template"]');
    }
}
