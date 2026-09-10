<?php

namespace Webkul\ImageCache\Exceptions;

class InvalidTemplate extends \Exception
{
    /**
     * Create an instance.
     */
    public function __construct(string $themeCode, string $name, string $template)
    {
        parent::__construct("Image template [{$name}] of theme [{$themeCode}] must be a class with an applyFilter() method, [{$template}] given.");
    }
}
