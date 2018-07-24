<?php

namespace Webkul\Core\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Dimsav\Translatable\Translatable;
use Webkul\Core\Models\Locale;
use Webkul\Channel\Models\ChannelLocale;

class TranslatableModel extends Model
{
    use Translatable;

    /**
     * @param string $key
     *
     * @return bool
     */
    protected function isKeyALocale($key)
    {
        if(is_numeric($key)) {
            if(ChannelLocale::find($key))
                return true;
        } else {
            if(Locale::where('code', '=', $key)->first())
                return true;
        }
        
        return false;
    }

    /**
     * @return string
     */
    protected function locale()
    {
        return channel()->getDefaultChannelLocale()->id;
    }
}