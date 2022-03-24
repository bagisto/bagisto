<?php

namespace Tests\Unit\Core;

use UnitTester;
use Codeception\Example;

class CoreCest
{
    /**
     * @param \UnitTester          $I
     *
     * @param \Codeception\Example $scenario
     *
     * @throws \Exception
     * @dataProvider getTaxRateScenarios
     *
     */
    public function testTaxRateAsIdentifier(UnitTester $I, Example $scenario): void
    {
        $I->assertEquals(
            $scenario['expected'],
            $I->executeFunction(
                \Webkul\Core\Core::class,
                'taxRateAsIdentifier',
                [$scenario['input']]
            )
        );
    }

    protected function getTaxRateScenarios(): array
    {
        return [
            [
                'input'    => 0,
                'expected' => '0',
            ],
            [
                'input'    => 0.01,
                'expected' => '0_01',
            ],
            [
                'input'    => .12,
                'expected' => '0_12',
            ],
            [
                'input'    => 1234.5678,
                'expected' => '1234_5678',
            ],
        ];
    }
}
