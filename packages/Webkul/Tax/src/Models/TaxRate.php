<?php

namespace Webkul\Tax\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Webkul\Tax\Database\Factories\TaxRateFactory;
use Webkul\Tax\Contracts\TaxRate as TaxRateContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TaxRate extends Model implements TaxRateContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'tax_rates';

    protected $fillable = [
        'identifier',
        'is_zip',
        'zip_code',
        'zip_from',
        'zip_to',
        'state',
        'country',
        'tax_rate',
    ];

    public function tax_categories(): BelongsToMany
    {
        return $this->belongsToMany(TaxCategoryProxy::modelClass(), 'tax_categories_tax_rates', 'tax_rate_id', 'id');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return TaxRateFactory::new();
    }
}