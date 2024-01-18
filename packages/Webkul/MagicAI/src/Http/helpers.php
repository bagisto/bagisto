<?php

if (! function_exists('magic_ai')) {
    /**
     * MagicAI helper.
     *
     * @return \Webkul\MagicAI\MagicAI
     */
    function magic_ai()
    {
        return app('magic_ai');
    }
}
