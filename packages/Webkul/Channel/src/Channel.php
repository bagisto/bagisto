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

    public function getCurrentChannel() {
        //just retrieve only three columns id, name and code
        $current_channel = collect(ChannelModel::select('id', 'name', 'code')->first());
        return $current_channel;
    }

    public function getChannelModel() {
        return ChannelModel::first();
    }
}