<?php

namespace Webkul\Core\Tests\Concerns;

trait CoreAssertions
{
    /**
     * Assert model wise.
     */
    public function assertModelWise(array $modelWiseAssertions): void
    {
        foreach ($modelWiseAssertions as $modelClassName => $modelAssertions) {
            foreach ($modelAssertions as $assertion) {
                $this->assertDatabaseHas(app($modelClassName)->getTable(), $assertion);
            }
        }
    }

    /**
     * Assert that two numbers are equal with optional decimal precision.
     */
    public function assertEquality(float $expected, float $actual, ?int $decimal = null): void
    {
        $decimal = $decimal ?? core()->getCurrentChannel()->decimal;

        $expectedFormatted = number_format($expected, $decimal);

        $actualFormatted = number_format($actual, $decimal);

        $this->assertEquals($expectedFormatted, $actualFormatted);
    }
}
