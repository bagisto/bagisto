<?php

namespace Webkul\Core\Tests\Concerns;

trait CoreAssertions
{
    /**
     * Assert that two prices are equal with channel-aware decimal precision.
     */
    public function assertPrice(float $expected, float $actual, ?int $decimal = null): void
    {
        $decimal = $decimal ?? core()->getCurrentChannel()->decimal;

        $expectedFormatted = number_format($expected, $decimal);

        $actualFormatted = number_format($actual, $decimal);

        $this->assertEquals($expectedFormatted, $actualFormatted);
    }
}
