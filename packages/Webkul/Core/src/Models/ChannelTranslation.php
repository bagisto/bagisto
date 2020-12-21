<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\ChannelTranslation as ChannelTranslationContract;

class ChannelTranslation extends Model implements ChannelTranslationContract
{
    protected $guarded = [];
}