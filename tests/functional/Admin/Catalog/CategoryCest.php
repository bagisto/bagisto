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
        $I->amOnAdminRoute('admin.dashboard.index');
        $I->click(__('admin::app.layouts.catalog'), '//*[contains(@class, "navbar-left")]');
        $I->click(__('admin::app.layouts.categories'), '//*[contains(@class, "aside-nav")]');

        $I->seeCurrentRouteIs('admin.catalog.categories.index');
        $I->see($category->id, '//script[@type="text/x-template"]');
        $I->see($category->name, '//script[@type="text/x-template"]');
    }
}
