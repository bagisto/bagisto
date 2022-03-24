<?php

namespace Tests\API\V1\Shop\Catalog;

use ApiTester;

class CategoryCest extends CatalogCest
{
    public function testForFetchingAllTheCategories(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('categories'));

        $I->seeAllNecessarySuccessResponse();
    }
}
