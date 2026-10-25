<?php

namespace Webkul\Shop\Tests\Fixtures\ImageCache;

use Webkul\ImageCache\TemplateRegistry;

class CountingTemplateRegistry extends TemplateRegistry
{
    /**
     * How many template entries this registry has checked.
     */
    public int $checks = 0;

    /**
     * Count each template entry checked, then check it as the registry does.
     */
    protected function isValid(?string $themeCode, mixed $name, mixed $template): bool
    {
        $this->checks++;

        return parent::isValid($themeCode, $name, $template);
    }
}
