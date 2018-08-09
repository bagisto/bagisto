<?php

namespace Webkul\Channel;

use Webkul\Channel\Models\Channel as ChannelModel;

class Channel
{
    public function getAllChannels() {
        return ChannelModel::all();
    }

    public function getChannel() {
        $channel = ChannelModel::first();

        if(!$channel)
            return;

        return $channel->code;
    }
}