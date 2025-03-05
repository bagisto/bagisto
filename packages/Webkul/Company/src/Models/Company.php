<?php

namespace Webkul\Company\Models;

use MongoDB\Laravel\Eloquent\Model;
class Company extends Model
{

    protected $connection = 'mongodb';
    /**
     * The attributes that are mass assignable.
     *
     */
    protected $collection = 'companies';

    protected $primaryKey = 'id';
}
