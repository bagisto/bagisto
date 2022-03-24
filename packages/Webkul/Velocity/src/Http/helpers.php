<?php

if (! function_exists('velocity')) {
    /**
     * Velocity helper.
     *
     * @return \Webkul\Velocity\Velocity
     */
    function velocity()
    {
        return app()->make(\Webkul\Velocity\Velocity::class);
    }
}
