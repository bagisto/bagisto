<?php

namespace Webkul\ImageCache\Exceptions;

class InvalidTemplate extends \Exception
{
    /**
     * Create an instance for a template of a theme, or of `config/imagecache.php` when no theme is given.
     */
    public function __construct(?string $themeCode, string $name, string $reason)
    {
        $owner = is_null($themeCode) ? 'config/imagecache.php' : "theme [{$themeCode}]";

        parent::__construct("Image template [{$name}] of {$owner} is invalid: {$reason}.");
    }
}
