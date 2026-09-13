<?php

namespace Webkul\Product\Tests;

use Tests\TestCase;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\Product\Tests\Concerns\ProductTestBench;

class ProductTestCase extends TestCase
{
    use CoreAssertions, ProductTestBench;
}
