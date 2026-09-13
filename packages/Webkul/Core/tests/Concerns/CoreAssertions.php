<?php

namespace Webkul\Core\Tests\Concerns;

trait CoreAssertions
{
    /**
     * Assert each listed row is present on its model's table.
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
     * Assert that two prices are equal with channel-aware decimal precision.
     */
    public function assertPrice(float $expected, float $actual, ?int $decimal = null): void
    {
        expect($actual)->toBePrice($expected, $decimal);
    }
}
