<?php

namespace Webkul\Admin\Tests;

use Tests\TestCase;
use Webkul\Admin\Tests\Concerns\AdminTestBench;
use Webkul\Core\Tests\Concerns\CoreAssertions;

class AdminTestCase extends TestCase
{
    use AdminTestBench, CoreAssertions;
}
