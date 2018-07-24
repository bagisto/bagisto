<?php
    use Webkul\Channel\Channel;

    if (! function_exists('channel')) {
        function channel()
        {
            return new Channel;
        }
    }
?>