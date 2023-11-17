<?php

namespace Webkul\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketing\Contracts\SearchSynonym as SearchSynonymContract;

class SearchSynonym extends Model implements SearchSynonymContract
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'terms',
    ];
}
