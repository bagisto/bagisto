<?php

if (! function_exists('bouncer')) {
    function bouncer()
    {
        return app()->make(\Webkul\User\Bouncer::class);
    }
}
