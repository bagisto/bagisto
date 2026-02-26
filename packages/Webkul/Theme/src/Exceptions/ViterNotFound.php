<?php

namespace Webkul\Theme\Exceptions;

class ViterNotFound extends \Exception
{
    /**
     * Create an instance.
     *
     * @param  string  $theme
     * @return void
     */
    public function __construct($namespace)
    {
        parent::__construct("Viter with `$namespace` namespace not found. Please add `$namespace` namespace in the `config/bagisto-vite.php` file.", 1);
    }
}
