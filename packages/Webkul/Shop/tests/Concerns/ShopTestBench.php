<?php

namespace Webkul\Shop\Tests\Concerns;

use Webkul\Admin\Tests\Concerns\ProductTestBench;

trait ShopTestBench
{
    use AssertionHelpers;
    use AuthHelpers;
    use CartHelpers;
    use CheckoutHelpers;
    use PricingHelpers;
    use ProductTestBench;
}
