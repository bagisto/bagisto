<?php
    use Webkul\Core\Core;

    if (! function_exists('core')) {
        function core()
        {
            return new Core;
        }
    }
?>