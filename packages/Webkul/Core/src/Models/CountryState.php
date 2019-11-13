<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\CountryState as CountryStateContract;


class CountryState extends Model implements CountryStateContract
{
    public $timestamps = false;
}