<?php

namespace Tests\Functional\Admin\Customer;

use FunctionalTester;
use Webkul\Core\Helpers\Laravel5Helper;
use Webkul\Product\Models\ProductReview;

class ReviewCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $product = $I->haveProduct(Laravel5Helper::SIMPLE_PRODUCT, [], ['simple']);
        $review = $I->have(ProductReview::class, ['product_id' => $product->id]);

        $I->loginAsAdmin();
       
        $I->amOnAdminRoute('admin.customer.review.index');       
        $I->seeCurrentRouteIs('admin.customer.review.index');
      
        $I->see("{$review->id}", '//script[@type="text/x-template"]');
    }
}
