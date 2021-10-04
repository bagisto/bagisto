<?php

namespace Webkul\Tax\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Tax\Models\TaxCategory;
use Webkul\Tax\Models\TaxRate;
use Webkul\Tax\Database\Factories\TaxMapFactory;
use Webkul\Tax\Contracts\TaxMap as TaxMapContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxMap extends Model implements TaxMapContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tax_categories_tax_rates';

    protected $fillable = [
       'tax_category_id',
       'tax_rate_id',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return TaxMapFactory
     */
    protected static function newFactory(): TaxMapFactory
    {
        return TaxMapFactory::new();
    }

}