<?php

namespace Tests\Functional\Admin\Customer;


use FunctionalTester;
use Webkul\Product\Models\ProductReview;


class ReviewCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $product = $I->haveProduct([], ['simple']);
        $review = $I->have(ProductReview::class, ['product_id' => $product->id]);

        $I->loginAsAdmin();
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.customers'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.reviews'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.customer.review.index');
        $I->see($review->id, '//script[@type="text/x-template"]');
        $I->see($review->title, '//script[@type="text/x-template"]');
    }
}
