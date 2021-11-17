<?php

namespace Tests\Functional\Admin\Customer;

use FunctionalTester;
use Webkul\Core\Helpers\Laravel5Helper;
use Webkul\Product\Models\ProductReview;

class ReviewCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.customers'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.reviews'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.customer.review.index');
    }
}
