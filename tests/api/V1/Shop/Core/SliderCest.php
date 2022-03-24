<?php

namespace Tests\API\V1\Shop\Core;

use ApiTester;

class SliderCest extends CoreCest
{
    public function testForFetchingAllTheSliders(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('sliders'));

        $I->seeAllNecessarySuccessResponse();
    }
}
