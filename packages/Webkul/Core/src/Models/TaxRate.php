<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Models\TaxCategory;

class TaxRate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tax_rates';

    protected $fillable = [
        'identifier', 'is_zip_from', 'zip_from', 'zip_to', 'state', 'country', 'tax_rate'
    ];

    public function tax_categories() {
        return $this->belongsToMany(TaxCategory::class, 'tax_categories_tax_rates', 'tax_rate_id', 'id');
    }
}