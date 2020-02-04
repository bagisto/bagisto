<?php

namespace Tests\Unit\Core;

use UnitTester;

class CoreCest
{
    public function testTaxRateAsIdentifier(UnitTester $I)
    {
        $scenarios = [
            [
                'input'    => 0,
                'expected' => '0',
            ],
            [
                'input'    => 0.01,
                'expected' => '0_01',
            ],
            [
                'input' => .12,
                'expected' => '0_12',
            ],
            [
                'input' => 1234.5678,
                'expected' => '1234_5678',
            ],
        ];

        foreach ($scenarios as $scenario) {
            $I->assertEquals($scenario['expected'], $I->executeFunction(\Webkul\Core\Core::class, 'taxRateAsIdentifier', [$scenario['input']]));
        }
    }
}
