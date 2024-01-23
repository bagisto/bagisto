<?php

namespace Webkul\Shop\Tests;

use Tests\TestCase;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\Shop\Tests\Concerns\ShopSweeper;
use Webkul\Shop\Tests\Concerns\ShopTestBench;

class ShopTestCase extends TestCase
{
    use CoreAssertions, ShopSweeper, ShopTestBench;

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        $this->cleanAll();

        parent::tearDown();
    }
}
