<?php

namespace Tests\API\V1\Shop\Core;

use ApiTester;
use Webkul\Core\Models\Country;

class CountryCest extends CoreCest
{
    public function testForFetchingAllTheCountries(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('countries'));

        $I->seeAllNecessarySuccessResponse();
    }

    public function testForFetchingTheCountryById(ApiTester $I)
    {
        $country = Country::find(1);

        $I->sendGet($this->getVersionRoute('countries/' . $country->id));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'id'   => $country->id,
            'code' => $country->code,
            'name' => $country->name,
        ]);
    }

    public function testForFetchingStatesGroupByCountries(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('countries/states/groups'));

        $I->seeAllNecessarySuccessResponse();
    }
}
