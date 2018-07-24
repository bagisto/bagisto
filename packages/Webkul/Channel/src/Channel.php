<?php

namespace Webkul\Channel;

use Webkul\Channel\Models\Channel as ChannelModel;

class Channel
{
    public function getDefaultChannelLocale() {
        $channel = ChannelModel::first();

        return $channel->channel_locales()->first();
    }
}