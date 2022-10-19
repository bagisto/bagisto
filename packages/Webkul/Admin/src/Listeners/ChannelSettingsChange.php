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
    public function checkForMaintenanceMode($channel)
    {
        $channels = core()->getAllChannels();

        if ($channels->contains('is_maintenance_on', 1)) {
            Artisan::call('channel:down');
        } else {
            Artisan::call('channel:up');
        }
    }
}
