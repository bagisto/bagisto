<?php

namespace Webkul\FPC\Listeners;

use Spatie\ResponseCache\Facades\ResponseCache;

class Channel
{
    /**
     * After channel update.
     *
     * @param  \Webkul\Core\Contracts\Channel  $channel
     * @return void
     */
    public function afterUpdate($channel)
    {
        ResponseCache::clear();
    }
}
