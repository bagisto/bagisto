<?php

namespace Webkul\Theme\Exceptions;

use Webkul\Theme\Sections\SectionType;

class InvalidSectionType extends \Exception
{
    /**
     * Create an instance.
     */
    public function __construct(string $entry)
    {
        parent::__construct("Section type [{$entry}] must be a core section type or a class extending ".SectionType::class.'.');
    }
}
