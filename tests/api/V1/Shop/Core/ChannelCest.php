<?php

namespace Tests\API\V1\Shop\Core;

use ApiTester;
use Webkul\Core\Models\Channel;

class ChannelCest extends CoreCest
{
    public function testForFetchingAllTheChannels(ApiTester $I)
    {
        $I->sendGet($this->getVersionRoute('channels'));

        $I->seeAllNecessarySuccessResponse();
    }

    public function testForFetchingTheChannelById(ApiTester $I)
    {
        $channel = Channel::find(1);

        $I->sendGet($this->getVersionRoute('channels/' . $channel->id));

        $I->seeAllNecessarySuccessResponse();

        $I->seeResponseContainsJson([
            'id'   => $channel->id,
            'code' => $channel->code,
            'name' => $channel->name,
        ]);
    }
}
