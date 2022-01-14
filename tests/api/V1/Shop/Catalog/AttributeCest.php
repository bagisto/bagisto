<?php

namespace Tests\API\V1\Shop\Catalog;

use ApiTester;
use Webkul\Attribute\Models\Attribute;

class AttributeCest extends CatalogCest
{
    public function testForFetchingAllTheAttributes(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('attributes'));

        $I->seeAllNecessarySuccessResponse();
    }

    public function testForFetchingTheAttributeById(ApiTester $I)
    {
        $attribute = Attribute::find(1);

        $I->sendGet($this->getVersionRoute('attributes/' . $attribute->id));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'id'   => $attribute->id,
            'code' => $attribute->code,
            'type' => $attribute->type,
            'name' => $attribute->name,
        ]);
    }
}
