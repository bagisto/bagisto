<?php

namespace Webkul\Shop\Tests\Concerns;

use Webkul\Product\Tests\Concerns\ProductTestBench;
use Webkul\Sales\Tests\Concerns\OrderTestBench;

trait ShopTestBench
{
    use AssertionHelpers;
    use AuthHelpers;
    use CartHelpers;
    use CheckoutHelpers;
    use OrderTestBench;
    use PricingHelpers;
    use ProductTestBench;
}
