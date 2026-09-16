<?php

namespace Webkul\FPC\Tests;

use Tests\TestCase;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\FPC\Tests\Concerns\FPCTestBench;
use Webkul\Product\Tests\Concerns\ProductTestBench;

class FPCTestCase extends TestCase
{
    use CoreAssertions, FPCTestBench, ProductTestBench;
}
