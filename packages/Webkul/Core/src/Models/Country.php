<?php

namespace Webkul\Core\Models;

use Webkul\Core\Eloquent\TranslatableModel;
use Webkul\Core\Contracts\Country as CountryContract;

class Country extends TranslatableModel implements CountryContract
{
    public $timestamps = false;

    public $translatedAttributes = ['name'];

    protected $with = ['translations'];
}