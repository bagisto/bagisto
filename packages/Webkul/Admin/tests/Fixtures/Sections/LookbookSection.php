<?php

namespace Webkul\Admin\Tests\Fixtures\Sections;

use Webkul\Theme\Sections\SectionType;

class LookbookSection extends SectionType
{
    /**
     * Code the section is stored under.
     */
    protected string $code = 'lookbook';

    /**
     * Whether a channel may hold only one section of this type.
     */
    protected bool $singleton = true;
}
