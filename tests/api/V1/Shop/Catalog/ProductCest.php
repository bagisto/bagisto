<?php

namespace Tests\API\V1\Shop\Catalog;

use ApiTester;

class ProductCest extends CatalogCest
{
    public function testForFetchingAllTheProducts(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('products'));

        $I->seeAllNecessarySuccessResponse();
    }
}
