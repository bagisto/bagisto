<?php

namespace Webkul\Channel;

use Webkul\Channel\Models\Channel as ChannelModel;

class Channel
{
    public function getDefaultChannelLocaleCode() {
        $channel = ChannelModel::first();

        if(!$channel || !$channel->locales()->count())
            return;

        return $channel->code . '-' . $channel->locales()->first()->code;
    }

    public function getDefaultChannelLocale() {
        $channel = ChannelModel::first();

        return $channel->locales()->first();
    }

    public function getChannelWithLocales() {
        return ChannelModel::with('locales')->get();
    }
}