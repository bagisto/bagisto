<?php

namespace Webkul\Tax\Models;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\Tax\Contracts\TaxCategory as TaxCategoryContract;
use Webkul\Tax\Database\Factories\TaxCategoryFactory;

class TaxCategory extends Model implements TaxCategoryContract
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string
     */
    protected $table = 'tax_categories';

    /**
     * Fillable property for the model.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    /**
     * For joining the two way pivot table.
     */
    public function tax_rates(): BelongsToMany
    {
        return $this->belongsToMany(TaxRateProxy::modelClass(), 'tax_categories_tax_rates', 'tax_category_id')
            ->withPivot('id');
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): Factory
    {
        return TaxCategoryFactory::new();
    }
}
