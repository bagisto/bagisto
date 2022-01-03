<?php

namespace Tests\Functional\Admin\Catalog;

use FunctionalTester;
use Webkul\Category\Models\Category;

class CategoryCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $category = $I->have(Category::class);

        $I->loginAsAdmin();

        $I->amOnAdminRoute('admin.catalog.categories.index');
        $I->seeCurrentRouteIs('admin.catalog.categories.index');

        $I->see("{$category->id}", '//script[@type="text/x-template"]');
    }
}
