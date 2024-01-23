<?php

namespace Webkul\Admin\Tests;

use Tests\TestCase;
use Webkul\Admin\Tests\Concerns\AdminSweeper;
use Webkul\Admin\Tests\Concerns\AdminTestBench;
use Webkul\Core\Tests\Concerns\CoreAssertions;

class AdminTestCase extends TestCase
{
    use AdminSweeper, AdminTestBench, CoreAssertions;

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanAll();
    }
}
