<?php

namespace Tests\API\V1\Shop\Catalog;

use ApiTester;
use Webkul\Attribute\Models\AttributeFamily;

class AttributeFamilyCest extends CatalogCest
{
    public function testForFetchingAllTheAttributeFamilies(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('attribute-families'));

        $I->seeAllNecessarySuccessResponse();
    }

    public function testForFetchingTheAttributeFamilyById(ApiTester $I)
    {
        $attributeFamily = AttributeFamily::find(1);

        $I->sendGet($this->getVersionRoute('attribute-families/' . $attributeFamily->id));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'id'     => $attributeFamily->id,
            'code'   => $attributeFamily->code,
            'name'   => $attributeFamily->name,
            'groups' => [],
        ]);
    }
}
