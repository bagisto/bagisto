<?php

namespace Webkul\Marketing\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Marketing\Contracts\SearchTerm as SearchTermContract;
use Webkul\Marketing\Database\Factories\SearchTermsFactory;

class SearchTerm extends Model implements SearchTermContract
{
    use HasFactory;

    /**
     * Define the table name for the model.
     *
     * @var string
     */
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

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return SearchTermsFactory::new();
    }
}
