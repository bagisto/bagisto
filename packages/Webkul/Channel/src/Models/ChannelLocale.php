<?php

namespace Webkul\Channel\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\Locale;

class ChannelLocale extends Model
{
    /**
     * Get the locale
     */
    public function locale()
    {
        return $this->belongsTo(Locale::class);
    }
}