<?php

namespace Webkul\Sales\Tests;

use Tests\TestCase;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\Product\Tests\Concerns\ProductTestBench;
use Webkul\Sales\Tests\Concerns\OrderTestBench;

class SalesTestCase extends TestCase
{
    use CoreAssertions, OrderTestBench, ProductTestBench;
}
