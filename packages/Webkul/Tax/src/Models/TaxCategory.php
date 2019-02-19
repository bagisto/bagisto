<?php

namespace Webkul\Tax\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Tax\Contracts\TaxCategory as TaxCategoryContract;

class TaxCategory extends Model implements TaxCategoryContract
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
        return $this->belongsToMany(TaxRateProxy::modelClass(), 'tax_categories_tax_rates', 'tax_category_id')->withPivot('id');
    }
}