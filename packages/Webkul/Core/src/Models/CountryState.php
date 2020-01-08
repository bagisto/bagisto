<?php

namespace Webkul\Core\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Core\Contracts\CountryState as CountryStateContract;


class CountryState extends TranslatableModel implements CountryStateContract
{
    public $translatedAttributes = ['name'];
    
    public $timestamps = false;
}