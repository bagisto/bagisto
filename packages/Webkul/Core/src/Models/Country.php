<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\Country as CountryContract;

class Country extends Model implements CountryContract
{
    public $timestamps = false;
}