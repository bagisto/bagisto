<?php

namespace Webkul\Admin\Tests;

use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use Webkul\Admin\Tests\Concerns\AdminTestBench;
use Webkul\Core\Tests\Concerns\CoreAssertions;
use Webkul\Product\Tests\Concerns\ProductTestBench;
use Webkul\Sales\Tests\Concerns\OrderTestBench;
use Webkul\Theme\Facades\Themes;
use Webkul\Theme\Themes as ThemeRegistry;

class AdminTestCase extends TestCase
{
    use AdminTestBench, CoreAssertions, OrderTestBench, ProductTestBench;

    /**
     * Send a request with a theme registry resolved for that request alone, as it is in production.
     *
     * The registry decides once, when it is first resolved, whether the request is an admin one and only
     * registers that side's themes. A fixture that renders a storefront mail before the first admin
     * request, or an admin test that then renders the storefront preview, would otherwise carry the
     * themes of the wrong side into the request under test.
     */
    public function call($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null): TestResponse
    {
        Themes::clearResolvedInstance(ThemeRegistry::class);

        return parent::call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }
}
