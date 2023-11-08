<?php

namespace Webkul\Marketing\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketing\Contracts\SearchTerm as SearchTermContract;

class SearchTerm extends Model implements SearchTermContract
{
    protected $table = 'search_terms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'term',
        'results',
        'uses',
        'redirect_url',
        'display_in_suggested_terms',
        'locale',
        'channel_id',
    ];
}
