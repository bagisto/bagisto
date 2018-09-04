<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\TaxRate;
use Webkul\Core\Models\TaxMap;

class TaxCategory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tax_categories';

    protected $fillable = [
       'channel_id' ,'code', 'name' ,'description'
    ];

    //for joining the two way pivot table
    public function tax_rates() {
        return $this->belongsToMany(TaxRate::class, 'tax_categories_tax_rates', 'tax_category_id');
    }
}