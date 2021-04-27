<?php

namespace Webkul\Admin\Listeners;

use Illuminate\Support\Facades\Artisan;

class ChannelSettingsChange
{
    /**
     * Check for maintenance mode and set according to settings.
     *
     * @param  \Webkul\Core\Models\Channel  $channel
     * @return void
     */
    public function checkForMaintenaceMode($channel)
    {
        if ((bool) $channel->is_maintenance_on) {
            Artisan::call('down');
        } else {
            Artisan::call('up');
        }
    }
}