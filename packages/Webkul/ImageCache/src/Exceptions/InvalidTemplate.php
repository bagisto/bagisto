<?php

namespace Webkul\ImageCache\Exceptions;

class InvalidTemplate extends \Exception
{
    /**
     * Create an instance.
     */
    public function __construct(string $themeCode, string $name, string $reason)
    {
        parent::__construct("Image template [{$name}] of theme [{$themeCode}] is invalid: {$reason}.");
    }
}
