<?php

namespace Tests\API\V1\Shop\Core;

use ApiTester;
use Webkul\Core\Models\Locale;

class LocaleCest extends CoreCest
{
    public function testForFetchingAllTheLocales(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('locales'));

        $I->seeAllNecessarySuccessResponse();
    }

    public function testForFetchingTheLocaleById(ApiTester $I)
    {
        $locale = Locale::find(1);

        $I->sendGet($this->getVersionRoute('locales/' . $locale->id));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'id'   => $locale->id,
            'code' => $locale->code,
            'name' => $locale->name,
        ]);
    }
}
