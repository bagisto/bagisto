<?php

namespace Webkul\FPC\Tests;

use Tests\TestCase;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\FPC\Tests\Concerns\FPCTestBench;

class FPCTestCase extends TestCase
{
    use CoreAssertions, FPCTestBench;
}
