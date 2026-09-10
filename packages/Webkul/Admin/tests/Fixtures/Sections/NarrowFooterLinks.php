<?php

namespace Webkul\Admin\Tests\Fixtures\Sections;

use Webkul\Theme\Sections\FooterLinks;

class NarrowFooterLinks extends FooterLinks
{
    /**
     * Most columns the theme's footer lays out.
     */
    protected ?int $maxColumns = 3;
}
