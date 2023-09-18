<?php

namespace Webkul\Shop\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Shop\Contracts\ThemeCustomizationChannels as ThemeCustomizationChannelsContract;


class ThemeCustomizationChannels extends Model implements ThemeCustomizationChannelsContract
{
    use HasFactory;
}
