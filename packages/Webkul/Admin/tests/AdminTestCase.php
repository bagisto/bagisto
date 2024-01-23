<?php

namespace Webkul\Admin\Tests;

use Tests\TestCase;
use Webkul\Admin\Tests\Concerns\AdminSweeper;
use Webkul\Admin\Tests\Concerns\AdminTestBench;

class AdminTestCase extends TestCase
{
    use AdminSweeper, AdminTestBench;

    /**
     * Tear down.
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        $this->cleanAll();
    }
}
