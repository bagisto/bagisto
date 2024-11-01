<?php

namespace Frosko\FairySync\Models\Sync;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $sku
 * @property int $synced
 * @property int $loaded
 * @property array $errors
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Collection $productAttributes
 */
class Product extends Model
{
    protected $connection = 'sync';

    protected $fillable = [
        'sku',
        'synced',
        'loaded',
        'errors',
    ];

    protected $casts = [
        'errors'     => 'json',
    ];

    public function productAttributes(): HasMany
    {
        return $this->hasMany(
            ProductAttribute::class,
            'product_id',
            'id',
        );
    }
}
