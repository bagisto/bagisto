<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\TaxCategory;
use Webkul\Core\Models\TaxRate;

class TaxMap extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tax_categories_tax_rates';

    protected $fillable = [
       'tax_category_id', 'tax_rate_id'
    ];

}