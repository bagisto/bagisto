<?php

use Webkul\MagicAI\Facades\MagicAI;

if (! function_exists('magic_ai')) {
    /**
     * MagicAI helper.
     *
     * @return \Webkul\MagicAI\MagicAI
     */
    function magic_ai()
    {
        return MagicAI::getFacadeRoot();
    }
}
