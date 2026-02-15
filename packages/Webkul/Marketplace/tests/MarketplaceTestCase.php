<?php

namespace Webkul\Marketplace\Tests;

use Tests\TestCase;
use Webkul\Admin\Tests\Concerns\AdminTestBench;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\Marketplace\Tests\Concerns\MarketplaceTestBench;
use Webkul\Shop\Tests\Concerns\ShopTestBench;

class MarketplaceTestCase extends TestCase
{
    use AdminTestBench, CoreAssertions, MarketplaceTestBench, ShopTestBench;
}
